<?php

namespace App\Helpers;

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

}