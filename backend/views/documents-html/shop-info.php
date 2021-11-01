<table style="font-size: 12px; width: 300px;">
    <tr>
        <td style="width: 380px;"><b>Название:</b> <?= $shop->name ?><br></td>
    </tr>
    <tr>
        <td style="width: 380px;"><b>ИНН:</b> 111222333<br></td>
    </tr>
    <tr>
        <td style="width: 380px;"><b>Юридический адрес:</b> ул. Героев Революции, 245<br></td>
    </tr>
    <tr>
        <td style="width: 380px;"><b>Директор:</b> Иванов Ивано Иванович<br></td>
    </tr>
</table>
<table style="border-collapse: collapse; font-size: 12px; margin-top: 15px;">
    <tr>
        <td style=" padding-right: 15px;">Код ОГРН</td>

        <?php
        for ($i = 1; $i <= 10; $i++) { ?>
            <td style=" border: 1px solid #000000; padding: 5px;"><?= $i ?></td>
            <?php
        } ?>
    </tr>
</table>
<div style="margin-top: 35px; font-size: 14px; margin-right: -30px;" align="center"><b>Данные по магазину "<?= $shop->name ?>"</b></div>
<div align="center" style="margin-top: 3px; font-size: 11px; margin-right: -30px;"><b><span style="color: blue;">
    <i>от
        <?= date("d.m.Y") ?> г.
    </i></span></b>
</div>
<table border="1" align="left" style="
                border-collapse: collapse; /*убираем пустые промежутки между ячейками*/
                border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px*/
                font-size: 12px;
                margin-top: 15px;">
    <tr>
        <td align="center" style="width: 180px" colspan="3"><b>Баланс на дату:</b></td>
    </tr>
    <tr>
        <td align="center"><b>#</b></td>
        <td align="center"><b>Дата</b></td>
        <td align="center"><b>Актуальный баланс на дату</b></td>
    </tr>
    <?php
    $num = 1;
    foreach ($result_balance as $key => $balanc): ?>
    <tr>
        <td align="center" style="width: 120px"><i><span style="color: blue;"><?= $num++ ?></i></span></td>
        <td align="center" style="width: 120px"><i><span style="color: blue;"><?= Yii::$app->myComponent->dateStr($key) ?></i></span></td>
        <td align="center" style="width: 120px"><i><span style="color: blue;"><?= $balanc ?></i></span></td>
    </tr>
    <?php
    endforeach; ?>
</table>

<table border="1" align="left" style="
                border-collapse: collapse; /*убираем пустые промежутки между ячейками*/
                border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px*/
                font-size: 12px;
                margin-top: 15px;">
    <tr>
        <td align="center" style="width: 180px" colspan="4"><b>Список прихода:</b></td>
    </tr>
    <tr>
        <td align="center"><b>#</b></td>
        <td align="center"><b>Дата</b></td>
        <td align="center"><b>Тип</b></td>
        <td align="center"><b>Сумма</b></td>
    </tr>
    <?php
    $num = 1;
    foreach ($enrollment as $key => $enrollment_one): ?>
        <tr>
            <td align="center" style="width: 120px"><i><span style="color: blue;"><?= $num++ ?></i></span></td>
            <td align="center" style="width: 120px"><i><span style="color: blue;"><?= Yii::$app->myComponent->dateStr($key) ?></i></span></td>
            <td align="center" style="width: 120px"><i><span style="color: blue;">Приход</i></span></td>
            <td align="center" style="width: 120px"><i><span style="color: blue;"><?= $enrollment_one ?></i></span></td>
        </tr>
    <?php
    endforeach; ?>
</table>

<table border="1" align="left" style="
                border-collapse: collapse; /*убираем пустые промежутки между ячейками*/
                border: 1px solid #000000; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px*/
                font-size: 12px;
                margin-top: 15px;">
    <tr>
        <td align="center" style="width: 180px" colspan="4"><b>Список расхода:</b></td>
    </tr>
    <tr>
        <td align="center"><b>#</b></td>
        <td align="center"><b>Дата</b></td>
        <td align="center"><b>Тип</b></td>
        <td align="center"><b>Сумма</b></td>
    </tr>
    <?php
    $num = 1;
    foreach ($outlay as $key => $outlay_one): ?>
        <tr>
            <td align="center" style="width: 120px"><i><span style="color: blue;"><?= $num++ ?></i></span></td>
            <td align="center" style="width: 120px"><i><span style="color: blue;"><?= Yii::$app->myComponent->dateStr($key) ?></i></span></td>
            <td align="center" style="width: 120px"><i><span style="color: blue;">Расход</i></span></td>
            <td align="center" style="width: 120px"><i><span style="color: blue;"><?= $outlay_one ?></i></span></td>
        </tr>
    <?php
    endforeach; ?>
</table>

<div style="margin-top: 8px; font-size: 11px; margin-right: -30px;">С данными ознакомлен:</div>
<br>
<table style="margin-top: -0px; font-size: 11px; margin-right: -60px;">
    <tr>
        <td align="left" style="width: 380px;">
            <span style="color: blue;"><span style="color: blue;"><i>______________, ______________</i></span>
        </td>

        <td align="center" style="width: 70px;"></td>

        <td align="center" style="width: 70px;">М.П.</td>
    </tr>
    <tr>
        <td align="left" style="width: 380px;"><span style="color: blue;"><span style="color: blue;"><i><br><br>Председатель организации: <br>______________, ______________</i></span>
        </td>
        <td align="center" style="width: 70px;"></td>
        <td align="center" style="width: 70px;"><br><br><br><br>М.П.</td>
    </tr>
</table>
