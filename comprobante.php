<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Reutilizamos la conexión global de api-rma
require_once "../api-rma/db.php";

if (!isset($pdo)) {
    die("Protocolo caído. No hay conexión con el núcleo de la base de datos.");
}

// 🔐 RECONOCIMIENTO Y DESENCRIPTACIÓN DE TOKEN SEGURO
$token = $_GET["token"] ?? "";

if ($token === "") {
    die("<div style='color:red; font-family:monospace; padding:20px;'><strong>ACCESO DENEGADO:</strong> Token de transmisión ausente o inválido.</div>");
}

try {
    // Decodificación de cadena Base64
    $decodificado = base64_decode($token);
    $partes = explode("||", $decodificado);

    if (count($partes) !== 2) {
        throw new Exception("El hash de acceso ha sido alterado o está corrupto.");
    }

    $numero_caso = $partes[0];
    $hash_recibido = $partes[1];

    // Clave secreta compartida
    $secret_salt = "MICRO_EXPRESS_SECURE_TOKEN_2026";
    $hash_esperado = md5($numero_caso . $secret_salt);

    if ($hash_recibido !== $hash_esperado) {
        throw new Exception("Firma digital inválida. Intento de intrusión detectado.");
    }

    // Consulta SQL conectada a tus tablas reales
    $sql = "SELECT 
                c.id,
                c.numero_caso,
                c.equipo,
                c.marca,
                c.modelo,
                c.numero_serie,
                c.descripcion_problema,
                c.fecha_ingreso,
                c.id_tipo_caso,
                cl.nombre AS cliente_nombre,
                cl.cedula AS cliente_cedula,
                cl.celular AS cliente_celular,
                tc.nombre AS tipo_caso_nombre,
                ec.nombre AS estado_actual_nombre
            FROM casos c
            INNER JOIN clientes cl ON c.id_cliente = cl.id
            INNER JOIN tipos_caso tc ON c.id_tipo_caso = tc.id
            INNER JOIN estados_caso ec ON c.id_estado_actual = ec.id
            WHERE c.numero_caso = ?
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$numero_caso]);
    $caso = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$caso) {
        throw new Exception("El identificador de caso solicitado no existe en los registros físicos.");
    }

    $fecha_ingreso_formateada = date("d/m/Y", strtotime($caso['fecha_ingreso']));

    // Generación de rutas dinámicas de códigos QR y Barras
    $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $url_visualizacion = $protocolo . $host . "/rma-app/adm-rma/consulta?busqueda=" . urlencode($caso['numero_caso']);

    $qr_image_url = "https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=" . urlencode($url_visualizacion);
    $barcode_url = "https://barcode.tec-it.com/barcode.ashx?data=" . urlencode($caso['numero_caso']) . "&code=Code128&translate-esc=true";

} catch (Exception $e) {
    die("<div style='color:red; font-family:monospace; padding:20px;'><strong>CRITICAL ERROR:</strong> " . $e->getMessage() . "</div>");
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Nota <?php echo htmlspecialchars($caso['numero_caso']); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000;
            margin: 0;
            padding: 20px;
            background-color: #fff;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .title-main {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }

        .title-sub {
            font-size: 11px;
            font-weight: bold;
            margin: 0;
        }

        .info-header-text {
            font-size: 9px;
            color: #333;
            line-height: 1.3;
        }

        .doc-number-box {
            font-size: 14px;
            font-weight: bold;
            text-align: right;
        }

        .client-info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .client-info-table td {
            padding: 5px 4px;
            border-bottom: 1px solid #000;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .data-table th {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 6px 4px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
        }

        .data-table td {
            border-bottom: 1px solid #000;
            padding: 8px 4px;
        }

        .checkbox-container {
            margin: 20px 0;
        }

        .checkbox-item {
            display: inline-block;
            margin-right: 30px;
            font-weight: bold;
            font-size: 10px;
        }

        .box-square {
            display: inline-block;
            width: 11px;
            height: 11px;
            border: 1px solid #000;
            margin-right: 5px;
            vertical-align: middle;
            text-align: center;
            line-height: 11px;
            font-size: 10px;
        }

        .obs-section {
            margin-bottom: 20px;
            font-size: 11px;
        }

        .conditions-section {
            font-size: 9px;
            margin-bottom: 40px;
            line-height: 1.4;
        }

        .conditions-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .conditions-section ol {
            margin: 0;
            padding-left: 15px;
        }

        .footer-receipt {
            border-top: 1px dashed #000;
            padding-top: 20px;
        }

        .footer-columns-table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 35px;
        }

        .signature-line {
            border-top: 1px solid #000;
            width: 180px;
            text-align: center;
            font-size: 9px;
            padding-top: 3px;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                padding: 0;
                margin: 0;
            }

            @page {
                size: letter;
                margin: 1cm;
            }
        }
    </style>
</head>

<body>

    <div class="no-print" style="text-align: right; margin-bottom: 15px;">
        <button onclick="window.print();"
            style="padding: 8px 16px; background: #00ff66; border: 1px solid #000; font-family: monospace; font-weight: bold; cursor: pointer; border-radius: 4px;">
            [PRINT_PDF]
        </button>
    </div>

    <table class="header-table">
        <tr>
            <td style="width: 70%; vertical-align: top;">
                <div class="title-sub">DEPARTAMENTO TÉCNICO</div>
                <div class="title-main">Micro Express</div>
                <div class="title-sub" style="letter-spacing: 3px;">INFORMÁTICA</div>
                <div class="info-header-text" style="margin-top: 6px;">
                    VENTA AL POR MAYOR DE EQUIPOS INFORMÁTICOS Y SOFTWARE<br>
                    Avda. J. Kubitschek 796 c/ Celsa Speratti<br>
                    Teléfonos: (595-21) 226 979 (R.A.) / 225 037 / 214 539 / 204 886 - Fax: 226 751<br>
                    Asunción, Paraguay
                </div>
            </td>
            <td style="width: 30%; text-align: right; vertical-align: top;">
                <div class="doc-number-box">N° <?php echo htmlspecialchars($caso['numero_caso']); ?></div>
                <div style="font-size: 11px; font-weight: bold; margin-top: 10px;">FECHA:
                    <?php echo $fecha_ingreso_formateada; ?>
                </div>
            </td>
        </tr>
    </table>

    <table class="client-info-table">
        <tr>
            <td style="width: 10%; font-weight: bold;">CLIENTE:</td>
            <td style="width: 55%; text-transform: uppercase;"><?php echo htmlspecialchars($caso['cliente_nombre']); ?>
            </td>
            <td style="width: 8%; font-weight: bold; text-align: right;">TELEF.:</td>
            <td style="width: 27%;"><?php echo htmlspecialchars($caso['cliente_celular']); ?></td>
        </tr>
        <tr>
            <td style="font-weight: bold;">C.I. / RUC:</td>
            <td colspan="3"><?php echo htmlspecialchars($caso['cliente_cedula']); ?></td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25%;">SERIE N°</th>
                <th style="width: 50%;">DETALLE EQUIPO</th>
                <th style="width: 25%;">MODELO</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-family: monospace; font-weight: bold;">
                    <?php echo htmlspecialchars($caso['numero_serie']); ?>
                </td>
                <td style="text-transform: uppercase;">
                    <?php echo htmlspecialchars($caso['equipo'] . " " . $caso['marca']); ?>
                </td>
                <td style="text-transform: uppercase;"><?php echo htmlspecialchars($caso['modelo']); ?></td>
            </tr>
        </tbody>
    </table>

    <div class="checkbox-container">
        <div class="checkbox-item">
            <span class="box-square"><?php echo ($caso['id_tipo_caso'] == 1) ? '✓' : ''; ?></span> GARANTÍA
        </div>
        <div class="checkbox-item">
            <span class="box-square"><?php echo ($caso['id_tipo_caso'] == 2) ? '✓' : ''; ?></span> CONDICIONAL
            DEVOLUCIÓN
        </div>
        <div class="checkbox-item">
            <span class="box-square"><?php echo ($caso['id_tipo_caso'] == 3) ? '✓' : ''; ?></span> A VERIFICAR
        </div>
        <div class="checkbox-item">
            <span class="box-square"><?php echo (!in_array($caso['id_tipo_caso'], [1, 2, 3])) ? '✓' : ''; ?></span>
            OTROS
        </div>
    </div>

    <div class="obs-section">
        <strong>Obs.:</strong> <?php echo htmlspecialchars($caso['descripcion_problema']); ?>
    </div>

    <div class="conditions-section">
        <div class="conditions-title">CONDICIONES GENERALES</div>
        <ol>
            <li>Queda debidamente aclarado que pasado los 30 (treinta) días el cliente pierde todo derecho a reclamo por
                las mercaderías que se mencionan en esta nota.</li>
            <li>Solo con la presentación de esta nota podrá reclamar las mercaderías mencionadas.</li>
            <li>La devolución de las mercaderías se hará solo dentro de las primeras 48 horas de la fecha de compra.
            </li>
            <li>Se reciben las mercaderías en el estado en que se encuentran con la conformidad del cliente.</li>
        </ol>
    </div>

    <div class="footer-receipt">
        <table class="footer-columns-table">
            <tr>
                <td style="width: 65%; vertical-align: top;">
                    <div class="title-sub" style="font-size: 12px; font-weight: bold;">MICRO EXPRESS S.R.L.</div>
                    <div style="font-size: 11px; font-weight: bold; margin: 4px 0;">N°
                        <?php echo htmlspecialchars($caso['numero_caso']); ?></div>

                    <table style="font-size: 10px; line-height: 1.5; margin-top: 8px;">
                        <tr>
                            <td style="font-weight:bold; width: 60px;">Cliente:</td>
                            <td><?php echo htmlspecialchars($caso['cliente_nombre']); ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold;">Equipo:</td>
                            <td><?php echo htmlspecialchars($caso['equipo'] . " " . $caso['marca']); ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold;">Modelo:</td>
                            <td><?php echo htmlspecialchars($caso['modelo']); ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold;">Serie:</td>
                            <td><?php echo htmlspecialchars($caso['numero_serie']); ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold;">Ingreso:</td>
                            <td><?php echo $fecha_ingreso_formateada; ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold;">Estado:</td>
                            <td style="text-transform: uppercase; font-weight: bold; color: #444;">
                                <?php echo htmlspecialchars($caso['estado_actual_nombre']); ?>
                            </td>
                        </tr>
                    </table>
                </td>

                <td style="width: 35%; text-align: right; vertical-align: top;">
                    <div style="display: inline-block; text-align: center;">
                        <img src="<?php echo $qr_image_url; ?>" alt="QR"
                            style="border: 1px solid #000; padding: 3px; background: #fff; width: 90px; height: 90px;" />
                        <br style="margin-bottom: 5px;">
                        <img src="<?php echo $barcode_url; ?>" alt="Barcode"
                            style="max-width: 140px; height: 32px; object-fit: contain; margin-top: 4px;" />
                    </div>
                </td>
            </tr>
        </table>

        <table class="signature-table">
            <tr>
                <td style="width: 40%;">
                    <div class="signature-line">Firma Cliente</div>
                </td>
                <td style="width: 40%;">
                    <div class="signature-line">Aclaración / C.I. N°</div>
                </td>
                <td
                    style="width: 20%; text-align: right; vertical-align: bottom; font-size: 8px; color: #777; font-family: monospace;">
                    [SISTEMA_RMA_AUTOMÁTICO]
                </td>
            </tr>
        </table>
    </div>

    <script>
        window.onload = function () {
            window.print();
        }
    </script>
</body>

</html>