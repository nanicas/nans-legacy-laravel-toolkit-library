<?php

function doubleDecimal(float $value)
{
    return number_format($value, 2, '.', '');
}

function keepOnlyNumbers($value)
{
    return preg_replace("/[^0-9]/", "", $value);
}

function formatPhone($value)
{
    if (empty($value)) {
        return ' - ';
    }

    $len = strlen($value);
    $isShort = ($len == 10);

    $formattedPhone = '(' . substr($value, 0, 2) . ') ' .
            substr($value, 2, ($isShort) ? 4 : 5) . '-' .
            substr($value, ($isShort) ? 6 : 7);

    return $formattedPhone;
}

function formatCnpjCpf($value)
{
    $CPF_LENGTH = 11;
    $cnpj_cpf = preg_replace("/\D/", '', $value);

    if (strlen($cnpj_cpf) === $CPF_LENGTH) {
        return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
    }

    return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
}

function moneyFormat($value)
{
    return number_format($value, 2);
}

function convertDateToBrDate(string $date)
{
    list($year, $month, $day) = explode('-', $date);

    return implode('/', [$day, $month, $year]);
}

function convertTextMonthToBRTextMonth(string $month)
{
    $months = array(
        'January' => 'Janeiro',
        'February' => 'Fevereiro',
        'March' => 'Março',
        'April' => 'Abril',
        'May' => 'Maio',
        'June' => 'Junho',
        'July' => 'Julho',
        'August' => 'Agosto',
        'September' => 'Setembro',
        'October' => 'Outubro',
        'November' => 'Novembro',
        'December' => 'Dezembro'
    );

    return $months[$month] ?? null;
}

function isValidCNPJ($cnpj)
{
    $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);

    // Valida tamanho
    if (strlen($cnpj) != 14)
        return false;

    // Verifica se todos os digitos são iguais
    if (preg_match('/(\d)\1{13}/', $cnpj))
        return false;

    // Valida primeiro dígito verificador
    for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
        $soma += $cnpj[$i] * $j;
        $j = ($j == 2) ? 9 : $j - 1;
    }

    $resto = $soma % 11;

    if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto))
        return false;

    // Valida segundo dígito verificador
    for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
        $soma += $cnpj[$i] * $j;
        $j = ($j == 2) ? 9 : $j - 1;
    }

    $resto = $soma % 11;

    return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
}

function isValidCPF($cpf)
{
    // Extrai somente os números
    $cpf = preg_replace('/[^0-9]/is', '', $cpf);

    // Verifica se foi informado todos os digitos corretamente
    if (strlen($cpf) != 11) {
        return false;
    }

    // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    // Faz o calculo para validar o CPF
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }

    return true;
}

function convertValueToString($value)
{
    if ($value === false) {
        return 'false';
    }
    if ($value === null) {
        return 'null';
    }
    if ($value === true) {
        return 'true';
    }

    return (string) $value;
}

function lessThanOrEqualMaxIntValue($value)
{
    return $value <= 2147483647;
}

function cleanRoute(string $route)
{
    return preg_replace("/[^a-zA-Z]/", "", $route);
}