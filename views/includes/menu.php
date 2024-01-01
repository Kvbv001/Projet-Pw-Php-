<!-- Sidebar -->
<div class="sidebar">
        <div class="logo"> </div>
        <ul class="menu">
            <li class="<?= $_GET['page'] === 'dashboard' ? "active" : "" ?> ">
                <a href="index.php?page=dashboard">
                    <i class="fa-solid fa-gauge"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="<?= $_GET['page'] === 'categorie' ? "active" : "" ?> ">
                <a href="index.php?page=categorie">
                    <i class="fa-solid fa-table-columns"></i>
                    <span> Categories</span>
                </a>
            </li>
            <li class="<?= $_GET['page'] === 'licencie' ? "active" : "" ?> ">
                <a href="index.php?page=licencie">
                    <i class="fa-solid fa-id-card"></i>
                    <span> Licencié</span>
                </a>
            </li>
            <li class="<?= $_GET['page'] === 'contact' ? "active" : "" ?> ">
                <a href="index.php?page=contact">
                    <i class="fa-solid fa-phone"></i>
                    <span> Contact</span>
                </a>
            </li>
            <li class="<?= $_GET['page'] === 'educateur' ? "active" : "" ?> ">
                <a href="index.php?page=educateur">
                    <i class="fa-solid fa-person-chalkboard"></i>
                    <span>Educateur</span>
                </a>
            </li>
          
        </ul>

    </div>
<!-- end Sidebar -->