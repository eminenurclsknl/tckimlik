<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TC Kimlik Numarası Doğrulama ve Üretme</title>
    <style>
        /* Sayfa stili */
        body {
            font-family:Arial, Helvetica, sans-serif;
            display: flex;
            justify-content:center;
            align-items: center;
            height: 100vh;
            background-color:#db7093;
            margin: 0;
        }
        .container {
            background: #fff0f5;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px;
            text-align: center;
        }
        /* Giriş alanı stili */
        .giris {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 90%;
        }
        /* Buton stili */
        .dogrula,.uret {
            padding: 10px 20px;
            margin: 10px 5px;
            border: none;
            border-radius: 4px;
            background-color: #008080;
            color: white;
            cursor: pointer;
            font-size: 16px;
        }
        /* Buton hover stili */
        .dogrula:hover, .uret:hover {
            background-color: #808080;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>TC Kimlik Numarası Doğrulama ve Üretme</h1>
        <form action="" method="POST">
            <!-- TC Kimlik numarası girişi -->
            <input class="giris" type="text" name="tckn" placeholder="TC Kimlik Numarası Girin">
            <!-- Doğrula butonu -->
            <input class="dogrula" type="submit" name="dogrula" value="Doğrula">
            <!-- Üret butonu -->
            <button class="uret" type="submit" name="uret">Üret</button>
        </form>
        <div>
            <?php
            
                // TC Kimlik numarasının geçerli olup olmadığını kontrol eden fonksiyon
                function kimlikNoGecerliMi($kimlikNumarası) {
                    // TC Kimlik numarası 11 haneli olmalı ve ilk hanesi 0 olmamalıdır.
                    if (strlen($kimlikNumarası) !== 11 || $kimlikNumarası[0] == '0' || !ctype_digit($kimlikNumarası)) 
                    {
                        return false;
                    }

                    // 1, 3, 5, 7, 9. hanelerin toplamı
                    $tekHaneToplam = $kimlikNumarası[0] + $kimlikNumarası[2] + $kimlikNumarası[4] + $kimlikNumarası[6] + $kimlikNumarası[8];
                    // 2, 4, 6, 8. hanelerin toplamı
                    $ciftHaneToplam = $kimlikNumarası[1] + $kimlikNumarası[3] + $kimlikNumarası[5] + $kimlikNumarası[7];
                     
                    // 10. hane kontrolü
                    if ((($tekHaneToplam * 7) - $ciftHaneToplam) % 10 != $kimlikNumarası[9]) 
                    {
                        return false;
                    }

                    // İlk 10 hanenin toplamının mod 10'u 11. haneye eşit olmalı
                    $toplam = 0;
                    for ($i = 0; $i < 10; $i++) 
                    {
                        $toplam += $kimlikNumarası[$i];
                    }
                    if ($toplam % 10 != $kimlikNumarası[10]) 
                    {
                        return false;
                    }

                   

                    return true;
                }

            // Form gönderimi POST methodu ile yapıldıysa
            if ($_SERVER["REQUEST_METHOD"] == "POST") 
            {
                // Üret butonuna basıldıysa
                if (isset($_POST['uret'])) 
                {
                    // Geçerli bir TC Kimlik numarası üret
                    $uretilenKimlikNo = kimlikNoUret();
                    echo "<p>Üretilen geçerli TC Kimlik numarası: $uretilenKimlikNo</p>";
                }
                else 
                {
                    if(empty($_POST['tckn']) )
                    {
                        echo "<p>Lütfen bir TC Kimlik Numarası giriniz!</p>";
                    }
                    else 
                    {
                        // Doğrula butonuna basıldıysa
                        if (isset($_POST['dogrula'])) 
                        {
                            $kimlikNumarası = $_POST['tckn'];
                            // TC Kimlik numarasının geçerli olup olmadığını kontrol et
                            if (kimlikNoGecerliMi($kimlikNumarası)) 
                            {
                                echo "<p>$kimlikNumarası geçerli bir TC Kimlik numarasıdır.</p>";
                            } 
                            else 
                            {
                                echo "<p>$kimlikNumarası geçerli bir TC Kimlik numarası değildir.</p>";
                            }
                        }
                    }
                }
            }

                // Geçerli bir TC Kimlik numarası üreten fonksiyon
                function kimlikNoUret() {
                    $kimlikNumarası = [];
                    
                    // İlk 9 hane rastgele oluşturuluyor
                    for ($i = 0; $i < 9; $i++) 
                    {
                        $kimlikNumarası[$i] = rand(1, 9); // İlk hane sıfır olamaz
                    }

                    // 1, 3, 5, 7, 9. hanelerin toplamı
                    $tekHaneToplam = $kimlikNumarası[0] + $kimlikNumarası[2] + $kimlikNumarası[4] + $kimlikNumarası[6] + $kimlikNumarası[8];
                    // 2, 4, 6, 8. hanelerin toplamı
                    $ciftHaneToplam = $kimlikNumarası[1] + $kimlikNumarası[3] + $kimlikNumarası[5] + $kimlikNumarası[7];
                    
                    // 10. hane
                    $kimlikNumarası[9] = (($tekHaneToplam * 7) - $ciftHaneToplam) % 10;

                
                    // İlk 10 hanenin toplamının mod 10'u 11. haneye eşit olmalı
                    $toplam = 0;
                    for ($i = 0; $i < 10; $i++) 
                    {
                        $toplam += $kimlikNumarası[$i];
                    }
                    $kimlikNumarası[10] = $toplam % 10;

                    // Dizi elemanlarını string olarak birleştiriyoruz
                    $kimlikNumarası =  implode('', $kimlikNumarası);

                    if (strlen($kimlikNumarası) !== 11 || $kimlikNumarası[0] == '0' || !ctype_digit($kimlikNumarası)) {
                        return kimlikNoUret();
                    }

                    return $kimlikNumarası;
                }
            ?>
        </div>
    </div>
</body>
</html>
