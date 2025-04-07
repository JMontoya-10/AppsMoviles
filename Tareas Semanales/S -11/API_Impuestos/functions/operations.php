<?php
define("IVA", 0.13);

function calcularImpuestos($salario) {
    if ($salario <= 0) {
        returnError("El valor del líquido debe ser mayor a 0.");
    }

    $monto_bruto = $salario / (1 - IVA);
    $renta = calcularRenta($monto_bruto);

    $monto_bruto = ($salario + $renta) / (1 - IVA);
    $iva = $monto_bruto * IVA;
    $renta = calcularRenta($monto_bruto);

    return [
        "monto_bruto" => round($monto_bruto, 2),
        "iva" => round($iva, 2),
        "renta" => round($renta, 2),
        "liquido" => round($monto_bruto - $iva - $renta, 2)
    ];
}

function calcularRenta($monto) {
    if ($monto <= 487.60) {
        return 0;
    } elseif ($monto <= 642.85) {
        return ($monto - 487.60) * 0.10 + 17.48;
    } elseif ($monto <= 915.81) {
        return ($monto - 642.85) * 0.10 + 32.70;
    } else {
        return ($monto - 915.81) * 0.30 + 60.00;
    }
}
?>
