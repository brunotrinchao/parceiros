<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Archive;
use App\Models\Product;
use Validator;
use Illuminate\Support\Facades\DB;
use App\Helpers\Helper;

class AdminArchiveController extends AdminController
{
    public function list($produto){
        $products = DB::table('products')
        ->select('id', 'name')
        ->where('slug', $produto)
        ->first();
        $produto = json_decode(json_encode($products), true);
        // $product_id = null;
        // $list = [];
        // foreach($products as $product){
        //     $list[$product->id] = Helper::createSlug($product->name);
        //     if(Helper::createSlug($product->name) == $produto){
        //         $product_id = $product->id;
        //     }
        // } 

        if($produto['id']){
            $archives = DB::table('archives')
            ->select('archives.id',
            'archives.name',
            'archives.product_id',
            'archives.file',
            'archives.text',
            'archives.date',
            'products.id as product_id',
            'products.name as product_name',
            'products.slug as product_slug')
            ->when(auth()->user()->level != "S", function ($query) use ($produto) {
                 $query->where('product_id', $produto['id']);
                 return $query;
            })
            ->join('products', 'products.id', '=', 'archives.product_id')
            ->orderBy('date', 'desc')
            ->get();
            return view('admin.arquivos.index', compact('archives'));
        }
        return redirect()->route('admin.imoveis.home');

    }

    public function download($produto, $id){
        $archive = Archive::find($id);
        return response()->download(storage_path('app/public/arquivos/' . $archive->file));
    }

    public function add(){
        $products = DB::table('products')
        ->select('id', 'name')
        ->get();
        return view('admin.administracao.arquivos.novo', compact('products'));
    }

    public function item($produto, $id){
        $products = DB::table('products')
        ->select('id', 'name')
        ->get();
        $product_id = null;
        $list = [];
        foreach($products as $product){
            $list[$product->id] = $product->name;
            if(Helper::createSlug($product->name) == $produto){
                $product_id = $product->id;
            }
        }

        if($product_id){
            $archives = DB::table('archives')
            ->where('product_id', $product_id)
            ->where('id', $id)
            ->get();
            $archives[0]->product = $produto;
            $archives[0]->product_format = $list[$product_id];
            $archive = $archives[0];
            return view('admin.arquivos.arquivo', compact('archive'));
        }
        return redirect()->route('admin.imoveis.home');
    }

    public function upload(Request $request, Archive $archive){
        $messagesRule = [
            'name.required' => 'O campo nome é obrigatório.',
            'file.required' => 'O campo arquivo é obrigatório.',
            'file.max' => 'O arquivo deve ter no máximo 5MB.',
            'date.required' => 'Informe a validade do matérial.',
            'product_id.required' => 'O campo produto é obrigatório.'
        ];
        $validator = Validator::make($request->all(), [
        'name' => 'required',
        'date' => 'required',
        'product_id' => 'required',
        'file' => 'required|max:5120',
        'file.*' => 'mimes:jpeg,png,jpg,gif,zip,rar,pdf,doc,docx,xls,xlsx',
        ], $messagesRule);

    
        if ($validator->passes()) {
            $fileExp = explode('.', $request->file->getClientOriginalName());
            $name = preg_replace('/\W+/', '-', strtolower(time().'-'.$fileExp[0]));
            $nameFile = "{$name}.{$fileExp[1]}";
            
            $upload = $request->file->storeAs('public/arquivos', $nameFile);
            
            if($upload){
                $archive->file = $nameFile;
                $archive->product_id = $request->product_id;
                $archive->name = $request->name;
                $archive->text = $request->text;
                $archive->date = date('Y-m-d', strtotime(str_replace('/','-',$request->date)));
                
                if($archive->save()){
                    $retorno['message'] = 'Arquivo cadastrado com sucesso.';
                    $retorno['success'] = true; 
                    return response()->json($retorno);
                }
            }
            $retorno['message'] = 'Erro ao fazer upload';
            $retorno['success'] = false; 
            return response()->json($retorno);

        }
        return response()->json(['error'=> $validator->errors()]);
    }
    
}
