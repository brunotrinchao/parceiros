<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\BancosCategoria;
use App\Models\BancosParceiros;
use Validator;

class AdminBancosParceirosController extends AdminController
{
    public function index(Request $request){
        $bancos_parceiros = DB::table('bancos_parceiros')
            ->select(
                'bancos_parceiros.id',
                'bancos_parceiros.category_id',
                'bancos_parceiros.product_id',
                'bancos_parceiros.name',
                'bancos_parceiros.description',
                'bancos_parceiros.status',
                'bancos_parceiros.image',
                'bancos_parceiros.created_at as date',
                'products.name as name_product',
                'products.slug as slug_product',
                'bancos_categorias.name as name_bancos_categorias'
            )
            ->join('products', 'products.id', '=', 'bancos_parceiros.product_id')
            ->join('bancos_categorias', 'bancos_categorias.id', '=', 'bancos_parceiros.category_id')
            ->orderby('bancos_parceiros.name')
            ->get();
            
            $produtos = $this->getProdutos();
            $bancosCategoria = BancosCategoria::get();
            return view('admin.administracao.bancos-parceiros.index', compact('bancos_parceiros', 'produtos', 'bancosCategoria'));
    }

    public function create(Request $request, BancosParceiros $bancosParceiros){
        if($request->method() == 'POST'){
            $messagesRule = [
                'name.required' => 'Título é obrigatório.',
                'product_id.required' => 'Produto é obrigatório.',
                'category_id.required' => 'Categoria é obrigatório.',
                'file.required' => 'O campo arquivo é obrigatório.',
                'file.max' => 'O arquivo deve ter no máximo 5MB.',
            ];
            $validatedData = Validator::make($request->all(), [
                'name' => 'required',
                'product_id' => 'required',
                'category_id' => 'required',
                'file' => 'required|max:5120',
                'file.*' => 'mimes:jpeg,png,jpg,gif,zip,rar,pdf,doc,docx,xls,xlsx',
            ], $messagesRule);
            
            if($validatedData->fails()){
                $arrMsg;
                foreach(json_decode($validatedData->messages()) as $t){
                    $arrMsg[] = '- '. $t[0];
                }
                $retorno['message'] = implode('<br>', $arrMsg);
                $retorno['success'] = false; 
                return response()->json($retorno);
            }

            $fileExp = explode('.', $request->file->getClientOriginalName());
            $name = preg_replace('/\W+/', '-', strtolower(time().'-'.$fileExp[0]));
            $nameFile = "{$name}.{$fileExp[1]}";
            
            $upload = $request->file->storeAs('public/bancos', $nameFile);

            if($upload){

                $bancosParceirosOrder = BancosParceiros::where('category_id', $request->category_id)
                ->where('product_id', $request->product_id)
                ->count();
                
                $bancosParceiros->name = $request->name;
                $bancosParceiros->product_id = $request->product_id;
                $bancosParceiros->category_id = $request->category_id;
                $bancosParceiros->description = $request->description;
                $bancosParceiros->order = $bancosParceirosOrder + 1;
                $bancosParceiros->image = $nameFile;
                $last_id = $bancosParceiros->insert($bancosParceiros);
                if($last_id['success']){
                    $dados['success'] = true;
                    $dados['message'] = 'Banco parceiro cadastrado com sucesso.';
                    return response()->json($dados);
                }
            }
            
            $dados['success'] = false;
            $dados['message'] = 'Erro ao cadastrar ajuda.';
            return response()->json($dados);
        }

        $produtos = $this->getProdutos();
        $bancosCategoria = BancosCategoria::get();
        $bancosParceiros = null;
        return view('admin.administracao.bancos-parceiros.novo', compact('bancosParceiros', 'produtos', 'bancosCategoria'));
    }

    private function getProdutos(){
        return Product::orderBy('name')->get();
    }
}
