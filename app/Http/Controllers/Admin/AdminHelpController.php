<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Help;
use App\Models\Product;
use Validator;

class AdminHelpController extends AdminController
{
    public function index(Request $request){
        $helps = DB::table('helps')
            ->select(
                'helps.id',
                'helps.category_id',
                'helps.product_id',
                'helps.name',
                'helps.description',
                'helps.status',
                'helps.created_at as date',
                'categories.name as name_category',
                'products.name as name_product',
                'products.slug as slug_product'
            )
            ->join('categories', 'categories.id', '=', 'helps.category_id')
            ->join('products', 'products.id', '=', 'helps.product_id')
            ->orderby('categories.name')
            ->orderby('helps.created_at')
            ->get();
            
            return view('admin.administracao.ajuda.index', compact('helps'));
    }
    public function indexUser(Request $request, $produto, $categoria = null){
        $helps = null;
        $session = session()->get('portalparceiros');
        $product_id = $session['produtos']['id_produto'];
        if($categoria){
            $helps = DB::table('helps')
                ->select(
                    'helps.id',
                    'helps.category_id',
                    'helps.product_id',
                    'helps.name',
                    'helps.description',
                    'helps.status',
                    'helps.created_at as date',
                    'categories.name as name_category'
                )
                ->where('product_id', $product_id)
                ->where('category_id', $categoria)
                ->join('categories', 'categories.id', '=', 'helps.category_id')
                ->orderby('categories.name')
                ->orderby('helps.created_at')
                ->get();
           }     
            $categories = DB::table('helps')
                            ->select('categories.id', 'categories.name')
                            ->join('categories', 'categories.id', '=', 'helps.category_id')
                            ->where('product_id', $product_id)
                            ->groupBy('category_id')
                            ->get();
            
            return view('admin.ajuda.index', compact('helps', 'categories'));
    }

    public function create(Request $request, Help $help){
        if($request->method() == 'POST'){
            $messagesRule = [
                'name.required' => 'Título é obrigatório.',
                'description.required' => 'Informações é obrigatório.',
                'category_id.required' => 'Categoria é obrigatório.',
                'product_id.required' => 'Produto é obrigatório.'
            ];
            $validatedData = Validator::make($request->all(), [
                'name' => 'required',
                'description' => 'required',
                'category_id' => 'required',
                'product_id' => 'required'
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

            $helpOrder = Help::where('category_id', $request->category_id)
            ->where('product_id', $request->product_id)
            ->count();
            
            $help->name = $request->name;
            $help->description = $request->description;
            $help->product_id = $request->product_id;
            $help->category_id = $request->category_id;
            $help->status = ($request->status == 'on')? 'A' : 'I';
            $help->order = $helpOrder + 1;
            
            if($help->save()){
                $dados['success'] = true;
                $dados['message'] = 'Ajuda cadastrada com sucesso.';
                return response()->json($dados);
            }
            
            $dados['success'] = false;
            $dados['message'] = 'Erro ao cadastrar ajuda.';
            return response()->json($dados);
        }
        $categories = Category::get();
        $produtos = Product::orderBy('name')->get();
        $help = null;
        return view('admin.administracao.ajuda.novo', compact('categories', 'help', 'produtos'));
    }

    public function edit($id){
        $help = Help::find($id);
        $produtos = Product::orderBy('name')->get();
        $categories = Category::get();
        return view('admin.administracao.ajuda.novo', compact('categories', 'help', 'produtos'));
    }

    public function update(Request $request){
        if($request->id){
            $messagesRule = [
                'name.required' => 'Título é obrigatório.',
                'description.required' => 'Informações é obrigatório.',
                'category_id.required' => 'Categoria é obrigatório.',
                'product_id.required' => 'Produto é obrigatório.'
            ];
            $validatedData = Validator::make($request->all(), [
                'name' => 'required',
                'description' => 'required',
                'category_id' => 'required',
                'product_id' => 'required'
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
            
            $help = Help::find($request->id);
            $help->name = $request->name;
            $help->description = $request->description;
            $help->product_id = $request->product_id;
            $help->category_id = $request->category_id;
            $help->product_id = $request->product_id;
            $help->status = ($request->status == 'on')? 'A' : 'I';
            if($help->save()){
                $dados['success'] = true;
                $dados['message'] = 'Ajuda atualizada com sucesso.';
                return response()->json($dados);
            }
            
            $dados['success'] = false;
            $dados['message'] = 'Erro ao atualizar ajuda.';
            return response()->json($dados);
        }
    }

    public function order(Request $request, Help $help){

        if($request->method() == 'POST'){
            $lista_id = explode('&', str_replace('item[]=', '', $request['lista']));
            $i = 0;
            foreach ($lista_id as $value) {
                $i++;
                $help = Help::where('category_id', $request->category_id)
                        ->where('id', $value)
                        ->update(['order' => $i]);
            }
        }
        $produtos = Product::orderBy('name')->get();
        $categories = Category::get();
        return view('admin.administracao.ajuda.ordenar', compact('categories', 'produtos'));
    }

    public function get($category_id, $product_id){
        $helps = DB::table('helps')
        ->select(
            'helps.id',
            'helps.category_id',
            'helps.name',
            'helps.description',
            'helps.status',
            'helps.created_at as date'
        )
        ->orderby('order')
        ->where('category_id', $category_id)
        ->where('product_id', $product_id)
        ->get();
        return response()->json($helps);
    }
}
