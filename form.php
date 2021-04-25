<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $data = array_map('trim', $_POST);
        $errors = [];
        $maxData = 255;

        if (empty($data['lastname'])) {
            $errors[] = 'Le nom ne peut pas être vide';
        }
        if (strlen($data['lastname']) > $maxData) {
            $errors[] = 'Vous devez utiliser un maximum de ' . $lastnameLength . '  caractères';
        }
        if (empty($data['firstname'])) {
            $errors[] = 'Le prénom ne peut pas être vide';
        }
        if (strlen($data['firstname']) > $maxData) {
            $errors[] = 'Vous devez utiliser un maximum de ' . $firstnameLength . ' caractères';
        }

 
        if(!empty($_FILES['files']['name'][0])) {
            $file = $_FILES['files'];
            $upload = array();
            $allowed = array('jpg', 'png', 'webp', 'gif');

            foreach($file['name'] as $location => $fileName) {
                $fileTmp = $file['tmp_name'][$location];
                $fileSize = $file['size'][$location];
                $fileError = $file['error'][$location];
                $fileExt = explode('.', $fileName);
                $fileExt = strtolower(end($fileExt));

                if(in_array($fileExt, $allowed)) {
                    if($fileError === 0) {
                        if($fileSize <= 1000000) {
                            $fileNewName = uniqid('', true) . '.' . $fileExt;
                            $fileFolder = 'upload/' . $fileNewName;

                            if(move_uploaded_file($fileTmp, $fileFolder)) {
                                $upload[$location] = $fileFolder;
                            } else {
                                $fail[$location] = "[{$fileName}] échec de l'upload.";
                            }
                        } else {
                            $fail[$location] = "[{$fileName}] est trop volumineux.";
                        }
                    } else {
                        $fail[$location] = "[{$fileName}] échec avec le code {$fileError}.";
                    }
                } else {
                    $fail[$location] = "[{$fileName}] le format '{$fileExt}' n'est pas autorisé.";
                }
            }
            if(!empty($fail)) {
                print_r($fail);
            }
        }
        if (empty($errors)) {

        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
        <link rel="stylesheet" href="style.css">
        <title>Formulaire</title>
    </head>

    <body>

    <div class="container">
    <div class="card mt-5" style="width: 40rem;">
        <div class="row justify-content-center">
            <div class="col-md-5 m-5">
                <h1>Formulaire</h1>
                    <?php if (!empty($errors)) : ?>
                            <ul class="error">
                                <?php foreach ($errors as $error) : ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                    <?php endif; ?>
                <form action="form.php" method="POST" enctype="multipart/form-data" class="order-form" novalidate>
                    <div class="row m-2">
                        <div class="col-md-5">        
                            <div class="form-group">
                                <label for="lastname">Prénom :</label>
                                <input type="text" id="lastname" name="lastname" placeholder="Last name" value="<?= $data['lastname'] ?? '' ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="row m-2">
                        <div class="col-md-5">        
                                <div class="form-group">
                                <label for="firstname">Nom :</label>
                                <input type="text" id="firstname" name="firstname" placeholder="First name" value="<?= $data['firstname'] ?? '' ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="row m-2">
                        <div class="col-md-7">        
                            <div class="form-group">
                                <label for="upload">Upload image : </label>    
                                <input type="file" name="files[]" id="upload" multiple>
                            </div>
                        </div>
                    </div>
                    <div class="row m-2">
                        <div class="col-md-5">        
                            <div class="form-group">
                                <button type="submit" class="btn btn-outline-danger">Envoyer</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>  
    </div>     
    </div>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>

    </body>
</html>