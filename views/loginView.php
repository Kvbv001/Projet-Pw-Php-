<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.0/css/boxicons.min.css" />
    <link rel="stylesheet" href="assets/css/login.css">
    <title>Connexion | Club Sportif</title>
</head>

<body>
    <div class="wrapper">
        <video autoplay loop muted play-inline class="back-video">
            <source src="assets/images/basket.mp4" type="video/mp4">
        </video>
        <form id="login_form" name="login_form" action="controllers/loginController.php" method="POST">
            <div class="login_box">
                <div class="login_header">
                    <span>Connexion</span>
                </div>
                <div class="input_box">
                    <input type="text" id="email" name="email" class="input-field" required>
                    <label for="email" class="label">Email</label>
                    <i class='bx bx-user icon'></i>
                    <small class="emailHelp"></small>
                </div>
                <div class="input_box">
                    <input type="password" id="password" name="password" class="input-field" required>
                    <label for="password" class="label">Mot de passe</label>
                    <i class='bx bx-lock-alt icon'></i>
                </div>
                <div class="input_box">
                    <input type="submit" class="input-submit" value="Se Connecter" id="bt_login">
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- pour les messages d'alert -->


    <script>
        // VERIFICATIONS DU FORMULAIRE


        // CONNEXION DE L'UTILISATEUR
        $('#login_form').on('submit', function(e) {
            e.preventDefault(); // Annuler le comportement par defaut du formulaire


            var form = $(this);
            var method = form.prop('method');
            var url = form.prop('action');

            $.ajax({
                type: method,
                data: form.serialize() + "&bt_login=" + true,
                url: url,
                success: function(result) {
                    donnee = JSON.parse(result);
                    if (donnee['success'] === 'true') {
                    

                        //    alert(donnee['message']);    
                        
                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: donnee['message'],
                            showConfirmButton: false,
                            timer: 1500
                        });
                        
                        location.href = "index.php?page=dashboard";
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: donnee['message']
                        });
                    }
                }
            });
        })
    </script>
</body>

</html>