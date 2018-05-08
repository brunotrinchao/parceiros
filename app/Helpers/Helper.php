<?php

namespace App\Helpers;

use PHPUnit\Framework\Constraint\IsTrue;
use App\Models\Product;


class Helper{
 
    /* Validador de CPF */
    public static function validaCPF($cpf){
        // Verifiva se o número digitado contém todos os digitos
        $cpf = preg_replace('/[^0-9]/i', '', $cpf);

        // Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
        if (strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999') {
            return false;
        } else {   // Calcula os números para verificar se o CPF é verdadeiro
            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf {
                        $c} * ( ($t + 1) - $c);
                }

                $d = ( (10 * $d) % 11) % 10;

                if ($cpf {
                    $c} != $d) {
                    return false;
                }
            }

            return true;
        }
    }

    public static function validaCNPJ($cnpj) {
        $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);

        if ($cnpj == "00000000000000" || 
        cnpj == "11111111111111" || 
        cnpj == "22222222222222" || 
        cnpj == "33333333333333" || 
        cnpj == "44444444444444" || 
        cnpj == "55555555555555" || 
        cnpj == "66666666666666" || 
        cnpj == "77777777777777" || 
        cnpj == "88888888888888" || 
        cnpj == "99999999999999"){
            return false;
        }
        // Valida tamanho
        if (strlen($cnpj) != 14)
            return false;
        // Valida primeiro dígito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
        {
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        if ($cnpj{12} != ($resto < 2 ? 0 : 11 - $resto))
            return false;
        // Valida segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
        {
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        if($cnpj{13} == ($resto < 2 ? 0 : 11 - $resto)){
            return true;
        }
    }

    public static function formatarCPF_CNPJ($campo, $formatado = true)
    {
        $codigoLimpo = ereg_replace("[' '-./ t]", '', $campo);
        $tamanho = (strlen($codigoLimpo) - 2);
        if ($tamanho != 9 && $tamanho != 12) {
            return false;
        }
        if ($formatado) {
            $mascara = ($tamanho == 9) ? '###.###.###-##' : '##.###.###/####-##';
            $indice = -1;
            for ($i = 0; $i < strlen($mascara); $i++) {
                if ($mascara[$i] == '#') {
                    $mascara[$i] = $codigoLimpo[++$indice];
                }
            }
            $retorno = $mascara;
        } else {
            $retorno = $codigoLimpo;
        }
        return $retorno;
    }

    public static function formatarTelefone($telefone = '')
    {
        $pattern = '/(\d{2})(\d{4})(\d*)/';
        $telefoneN = preg_replace($pattern, '($1) $2-$3', $telefone);

        return $telefoneN;
    }

    public static function formatarCEP($campo, $formatado = true)
    {
        $codigoLimpo = preg_replace('/[^0-9]/', '', $campo);
        $tamanho = (strlen($codigoLimpo));
        if ($tamanho != 8) {
            return null;
        }
        if ($formatado) {
            $mascara = '#####-###';
            $indice = -1;
            for ($i = 0; $i < strlen($mascara); $i++) {
                if ($mascara[$i] == '#') {
                    $mascara[$i] = $codigoLimpo[++$indice];
                }
            }
            $retorno = $mascara;
        } else {
            $retorno = $codigoLimpo;
        }
        return $retorno;
    }

    public static function validaEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) 
        && preg_match('/@.+\./', $email);
    }

    /* Desformata Telefone */
    public static function unFormatTelefone($telefone){
        return preg_replace('/[^0-9]/', '', $telefone);
    }

    public static function formatDate($date){
        return date('Y-m-d', strtotime(str_replace('/', '-', $date)));
    }

    public static function unFormatMoney($money){
        $retorno = str_replace('R$ ', '', $money);
        $retorno = str_replace('.', '', $retorno);
        $retorno = str_replace(',', '.', $retorno);
        return $retorno;
    }

    public static function checkPermission($permissions){
        $userAccess = Helper::getMyPermission(auth()->user()->level);
        // dd($permission);
        foreach ($permissions as $key => $value) {
            if($value == $userAccess){
                return true;
            }
        }
        return false;
    }

    private static function getMyPermission($nivel)
    {
        switch ($nivel) {
        case 'S':
            return 'superadmin';
            break;
        case 'A':
            return 'admin';
            break;
        case 'G':
            return 'gerente';
            break;
        case 'U':
            return 'usuario';
            break;
        }
    }

    public static function numberUnformat($number)
    {
        $ret = null;
        if (!empty($number)) {
            $ret = str_replace(',', '.', str_replace('.', '', $number));
            $ret = str_replace('R$ ', '', $ret);
            $ret = str_replace('% ', '', $ret);
        }
        return $ret;
    }
    public static function numberFormat($number)
    {
        $ret = null;
        if (!empty($number)) {
            $ret = number_format($number, 2, ',', '.');
        }
        return $ret;
    }

    public static function getIcon($ext){
        $ext = Helper::getExtension($ext);
        $retorno = 'file-o';
        switch ($ext) {
            case 'pdf':
                $retorno = 'file-pdf-o';
                break;
            case 'rar':
                $retorno = 'file-archive-o';
                break;
            case 'zip':
                $retorno = 'file-archive-o';
                break;
            case 'doc':
                $retorno = 'file-word-o';
                break;
            case 'docx':
                $retorno = 'file-word-o';
                break;
            case 'ppt':
                $retorno = 'file-powerpoint-o';
                break;
            case 'pptx':
                $retorno = 'file-powerpoint-o';
                break;
            case 'xls':
                $retorno = 'file-excel-o';
                break;
            case 'xlsx':
                $retorno = 'file-excel-o';
                break;
            case 'jpg':
                $retorno = 'file-image-o';
                break;
            case 'png':
                $retorno = 'file-image-o';
                break;

        }
        return $retorno;
    }

    public static function getExtension($file){
        $expFile = explode('/', $file);
        return pathinfo($expFile[count($expFile) - 1], PATHINFO_EXTENSION);
        
    }

    public static function createSlug($str, $delimiter = '-'){

        $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
        return $slug;
    
    } 

    public static function shortText($text, $chars_limit, $url = null){
        if (strlen($text) > $chars_limit){
            // If so, cut the string at the character limit
            $new_text = substr($text, 0, $chars_limit);
            // Trim off white space
            $new_text = trim($new_text);
            // Add at end of text ...
            $texto =  $new_text . '... <i class="fa fa-external-link"></i>';
            
            echo '<a href="'.$url.'" class="btn_ver_informacoes">'.$texto.'</a>';
        }
        // If not just return the text as is
        else
        {
        return $text;
        }
    }

    public static function listaStatus(){
        $array = [
            'A' => 'Aguardando contato',
            'C' => 'Contactado',
            'E' => 'Em negociação',
            'I' => 'Incosistente',
            'V' => 'Visitado'
        ];
        return $array;
    }

    public static function listaTipoImoveis(){
        $array = [
            'Apartamento' => [
                'Padrão',
                'Kitnet/Studio',
                'Loft',
                'Flat',
                'Cobertura',
                'Duplex',
                'Triplex',
                'Quarto',
            ],
            'Casa' => [
                'Padrão',
                'Casa de Condomínio',
                'Casa de Vila',
                'Sobrado',
                'Quarto',
            ],
            'Outros'=> [
                'Terreno',
                'Rural',
                'Comercial',
            ]
        ];
        return $array;
    }

    public static function styleWidget($i){
        $retorno = 'bg-aqua';
        switch ($i) {
            case 0:
            $retorno = ' bg-light-blue-active';
                break;
            case 1:
            $retorno = ' bg-aqua-active';
                break;
            case 2:
            $retorno = 'bg-green-active';
                break;
            case 3:
            $retorno = 'bg-yellow-active';
                break;
            case 4:
            $retorno = 'bg-red-active';
                break;
            case 5:
            $retorno = 'bg-teal-active';
                break;
            case 6:
            $retorno = 'bg-purple-active';
                break;
            case 7:
            $retorno = 'bg-orange-active';
                break;
            case 8:
            $retorno = 'bg-maroon-active';
                break;

        }
    }

    public static function filtroRelatorio($produto){
        $html = '';
        switch ($produto) {
            case 'imoveis':
                $html .= '<label>Tipo </label>';
                $html .= '<select name="tipo" style="width:100%">';
                $html .= '<option value="">.: Selecione :.</option>';
                $html .= '<optgroup label="Interessado">';
                $html .= '<option value="I-C">Comprar</option>';
                $html .= '<option value="I-A">Alugar</option>';
                $html .= '</optgroup>';
                $html .= '<optgroup label="Proprietário">';
                $html .= '<option value="P-V">Comprar</option>';
                $html .= '<option value="P-A">Alugar</option>';
                $html .= '</optgroup>';
                $html .= '</select>';
                break;
            case 'oi':
                $html .= '<label>Tipo </label>';
                $html .= '<select name="tipo" style="width:100%">';
                $html .= '<option value="">.: Selecione :.</option>';
                $html .= '<option value="F">Fechar contrato</option>';
                $html .= '<option value="A">Solicitar atendimento</option>';
                $html .= '</select>';
                break;
            case 'financiamento':
                $html .= '<label>Tipo </label>';
                $html .= '<select name="tipo" style="width:100%">';
                $html .= '<option value="">.: Selecione :.</option>';
                $html .= '<option value="T">Tradicional</option>';
                $html .= '<option value="R">Refinanciamento</option>';
                $html .= '</select>';
                break;
            case 'consultoria-de-credito':
                $html .= '<label>Tipo </label>';
                $html .= '<select name="tipo" style="width:100%">';
                $html .= '<option value="">.: Selecione :.</option>';
                $html .= '<option value="I">Imóvel</option>';
                $html .= '<option value="V">Veículo</option>';
                $html .= '</select>';
                break;

        }
        echo $html;
    }

    public static function geraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false){
        $lmin = 'abcdefghijklmnopqrstuvwxyz';
        $lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $num = '1234567890';
        $simb = '!@#$%*-';
        $retorno = '';
        $caracteres = '';
        $caracteres .= $lmin;
        if ($maiusculas) $caracteres .= $lmai;
        if ($numeros) $caracteres .= $num;
        if ($simbolos) $caracteres .= $simb;
        $len = strlen($caracteres);
        for ($n = 1; $n <= $tamanho; $n++) {
            $rand = mt_rand(1, $len);
            $retorno .= $caracteres[$rand-1];
        }
        return $retorno;
    }

    public static function listaProdutos(){
        $produtos = Product::all();
        dd($produtos);
    }

}