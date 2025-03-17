<?php
include "config/autoload.php";
include_once "common/header.php";
?>
<div class="container py-3">
    <div class="bg-white p-3 shadow-sm1 rounded">
        <div class="row">
            <div class="col-sm-10">
                <table class="table">
                    <tbody>
                        <tr>
                            <td width="30%">बिहार भूमि ब्रोकर आईडी </td>
                            <td>123456</td>
                        </tr>
                        <tr>
                            <td>नाम </td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>पिता का नाम </td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>जन्म तिथि </td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>आधार कार्ड नंबर </td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>पैन नंबर </td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>पता</td>
                            <td>-</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-sm-2">
                <div class="user-details-photo">
                    <div class="text-center mb-2">ID: 123456</div>
                    <img src="<?= theme_url('img/broker.jpg') ?>" class="img-fluid rounded" />
                </div>
            </div>
        </div>
        <div class="text-center py-3">
            <h5>नियम और शर्तें </h5>
        </div>
        <p>मैं एतद द्वारा घोषणा करता हूँ कि ऊपर दिए गए विवरण मेरी सर्वोत्तम जानकारी के अनुसार सत्य एवं सही है और मैं वचन देता हूँ की उसमे होने वाले किसी भी बदलाव के बारे में आपको तुरंत सूचित करे। यदि उपरोक्त में कोई जानकारी गलत या असत्य पायी जाती है या भ्रामक या गलत बयानी करने पर मुझे पता है की मुझे इसके लिए उत्तरदायी ठहराया जा सकता है। </p>

        <div class="text-center">
            <a href="#" class="btn btn-warning">Dashboard View</a>
            <a href="#" class="btn btn-warning">Download Broker Id</a>
        </div>
    </div>
</div>
<?php
include_once "common/footer.php";
