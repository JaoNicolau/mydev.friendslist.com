<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imoral - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <script src="models/Utilizador.js" defer></script>
    <script src="models/Produto.js" defer></script>
    <script src="models/Post.js" defer></script>
</head>
<body> 

<?php
require_once __DIR__ . '/../../app/config/Database.php';
require_once __DIR__ . '/../../app/dao/UserDAO.php';
require_once __DIR__ . '/../../app/dao/ProductDAO.php';

$userDAO = new UserDAO();
$users = $userDAO->arrayUsersDAO(); 

$camposvisiveis = ['image_id', 'id', 'username', 'email', 'is_admin', 'created_at'];
$camposEditaveis = ['username', 'email'];

$productDAO = new ProductDAO();
$products = $productDAO->arrayProductsDAO();

$camposvisiveisProdutos = ['image', 'id', 'nome', 'tipo', 'cor', 'preco_venda', 'preco_custo', 'stock', 'sales', 'revenue'];
$camposEditaveisProdutos = ['nome', 'tamanho', 'peso', 'tipo', 'cor', 'preco_venda', 'preco_custo', 'stock'];
?>

<?php if(isset($_SESSION['toast'])): ?>
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div class="toast show" role="alert">
            <div class="toast-body <?= $_SESSION['toast']['type'] === 'error' ? 'bg-danger' : 'bg-success' ?> text-white">
                <?= htmlspecialchars($_SESSION['toast']['message']) ?>
            </div>
        </div>
    </div>
    <?php unset($_SESSION['toast']); ?>
<?php endif; ?>
<style>

    /* 1. Estilo padrão do item (em repouso) */
    .menu-item {
        background-color: transparent !important; /* Fundo transparente */
        color: white !important;                 /* Texto branco */
        border: 1px solid #444 !important;       /* Borda cinza escura */
        transition: all 0.3s ease;               /* Deixa a mudança suave */
        width: 200px;
        text-align: center;
    }

    /* 2. Estilo de HOVER (Ao passar o mouse) */
    .menu-item:hover {
        background-color: rgba(255, 255, 255, 0.1) !important; /* Fundo levemente branco */
        color: #ffc107 !important;                            /* Texto fica amarelo */
        border-color: #ffc107 !important;                     /* Borda fica amarela */
    }

    /* 3. Estilo de ACTIVE (Quando clicado/selecionado) */
    /* O Bootstrap usa a classe .active para marcar o item atual */
    .menu-item.active {
        background-color: #ffc107 !important; /* Fundo amarelo vivo */
        color: black !important;             /* Texto preto para contrastar */
        border-color: #ffc107 !important;
        font-weight: bold;
    }

</style>

<!-- Símbolos SVG reutilizáveis -->
<svg xmlns="http://www.w3.org/2000/svg" class="d-none">
    <symbol id="home" viewBox="0 0 16 16">
        <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H2.5z"></path>
    </symbol>
    <symbol id="people" viewBox="0 0 16 16">
        <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8Zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022ZM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816ZM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z"/>
    </symbol>
    <symbol id="financesvg" viewBox="0 0 16 16">
        <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm.25 4a.75.75 0 0 1 .75.75v2h2a.75.75 0 0 1 0 1.5h-2v2a.75.75 0 0 1-1.5 0v-2h-2a.75.75 0 0 1 0-1.5h2v-2A.75.75 0 0 1 8.25 4z"/>
    </symbol>
    <symbol id="box" viewBox="0 0 16 16">
        <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5 8 5.961 14.154 3.5 8.186 1.113zM15 4.239l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"/>
    </symbol>
    <symbol id="cart" viewBox="0 0 16 16">
        <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
    </symbol>
    <symbol id="comunity-icon" viewBox="0 0 16 16">
        <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm.25 4a.75.75 0 0 1 .75.75v2h2a.75.75 0 0 1 0 1.5h-2v2a.75.75 0 0 1-1.5 0v-2h-2a.75.75 0 0 1 0-1.5h2v-2A.75.75 0 0 1 8.25 4z"/>
    </symbol>
    <symbol id="marketing-icon" viewBox="0 0 16 16">
        <path d="M3 2a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H3zm9.5 2a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0v-6a.5.5 0 0 1 .5-.5zM8.5 4a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0v-6a.5.5 0 0 1 .5-.5zM6.5 6a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0v-4A.5.5 0 0 1 6.5 6z"/>
    </symbol>
</svg>

<div class="container-fluid">
    <div class="row">  
        <!-- Sidebar fixa -->
        <div class="col-md-3 col-lg-2 d-md-block bg-black sidebar vh-100 p-3 position-fixed" style="width: 300px; margin-right: 70px;">
            <!-- Logo -->
            <div class="mb-4 text-center">
                <img src="Images/imoral_logo1.png" alt="logo_imoral" class="mb-3" style="width: 200px;">
            </div>
            
            <!-- menu de navegação -->
            <div class="list-group d-flex flex-column gap-3 align-items-center" role="tablist">
                <ul class="list-unstyled">
                    <li id="dashboradnav">
                        <a class="list-group-item list-group-item-action menu-item border rounded px-4 mb-2 text-center active" style="width: 200px; background-color: black;"
                        data-bs-toggle="list" href="#dashboard">
                            <svg class="me-2" width="16" height="16" style="fill: white;">
                                <use xlink:href="#home"></use>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li id="usersnav">
                        <a class="list-group-item list-group-item-action menu-item border rounded px-4 mb-2 text-center" style="width: 200px; background-color: black;"
                        data-bs-toggle="list" href="#users">
                            <svg class="me-2" width="16" height="16" style="fill: white;">
                                <use xlink:href="#people"></use>
                            </svg>
                            Users
                        </a>
                    </li>
                    <li id="financenav">
                        <a class="list-group-item list-group-item-action menu-item border rounded px-4 mb-2 text-center" style="width: 200px; background-color: black;"
                        data-bs-toggle="list" href="#finance">
                            <svg class="me-2" width="16" height="16" style="fill: white;">
                                <use xlink:href="#financesvg"></use>
                            </svg>
                            Finance
                        </a>
                    </li>
                    <li id="productsnav">
                        <a  class="list-group-item list-group-item-action menu-item border rounded px-4 mb-2 text-center" style="width: 200px; background-color: black;"
                        data-bs-toggle="list" href="#products">
                            <svg class="me-2" width="16" height="16" style="fill: white;">
                                <use xlink:href="#box"></use>
                            </svg>
                            Products
                        </a>
                    </li>
                    <li id="ordersnav">
                        <a class="list-group-item list-group-item-action menu-item border rounded px-4 mb-2 text-center" style="width: 200px; background-color: black;"
                        data-bs-toggle="list" href="#orders">
                            <svg class="me-2" width="16" height="16" style="fill: white;">
                                <use xlink:href="#cart"></use>
                            </svg>
                            Orders
                        </a>
                    </li>
                    <li id="comunitynav">
                        <a class="list-group-item list-group-item-action menu-item border rounded px-4 mb-2 text-center" style="width: 200px; background-color: black;"
                        data-bs-toggle="list" href="#comunity">
                            <svg class="me-2" width="16" height="16" style="fill: white;">
                                <use xlink:href="#comunity-icon"></use>
                            </svg>
                            Community
                        </a>
                    </li>
                    <li id="marketingnav">
                        <a class="list-group-item list-group-item-action menu-item border rounded px-4 mb-2 text-center" style="width: 200px; background-color: black;"
                        data-bs-toggle="list" href="#marketing">
                            <svg class="me-2" width="16" height="16" style="fill: white;">
                                <use xlink:href="#marketing-icon"></use>
                            </svg>
                            Marketing
                        </a>
                    </li>
                </ul>      
            </div>
        </div>
        <!-- end-sidebar -->
        <!-- Topbar -->
        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 bg-black" style="height: 60px; margin-left: 300px;">
        </div>
        <!-- end-topbar -->
        <!-- Conteúdo principal -->
        <div id="mainPage" class="col-md-9 ms-sm-auto col-lg-10 px-md-4" style="margin-left: 300px;">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="dashboard" role="tabpanel" style="margin-left: 70px;">
                    <h1 class="text-center mt-5">Dashboard</h1>
                    <p class="text-center">Here you can manage the main aspects of your business.</p>
                    <div>
                        <div class="row" style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center;">
                            <div class="col-md-5"
                            style="min-width: 300px;">
                                <div class="card card-stats">
                                    <div class="card-body" style="background-color: #f1f1f1; border-radius: 10px;">
                                        <div class="row">
                                            <div class="col">
                                                <p class="h5 card-title text-uppercase mb-0" style="color: black; font-weight: bold;">Sales Today</p>
                                                <span class="h2 mb-0" style="color: black;">15</span>
                                            </div>
                                            <div class="col-auto">
                                                <div class="icon icon-shape bg-dark text-white rounded-circle shadow"
                                                style="width: 50px; height: 50px;
                                                display: flex;
                                                align-items: center;
                                                justify-content: center;">
                                                    📈
                                                </div>
                                            </div>
                                        </div>
                                        <p class="mt-3 mb-0 text-muted text-sm">
                                            <span class="text-success me-2">+12%</span>
                                            <span class="text-nowrap">from yesterday</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5"
                            style="min-width: 300px;">
                                <div class="card card-stats">
                                    <div class="card-body" style="background-color: #f1f1f1; border-radius: 10px;">
                                        <div class="row">
                                            <div class="col">
                                                <p class="h5 card-title text-uppercase mb-0" style="color: black; font-weight: bold;">Customers Today</p>
                                                <span class="h2 mb-0" style="color: black;">11</span>
                                            </div>
                                            <div class="col-auto">
                                                <div class="icon icon-shape bg-dark text-white rounded-circle shadow"
                                                style="width: 50px; height: 50px;
                                                display: flex;
                                                align-items: center;
                                                justify-content: center;">
                                                    🧑‍🤝‍🧑
                                                </div>
                                            </div>
                                        </div>
                                        <p class="mt-3 mb-0 text-muted text-sm">
                                            <span class="text-success me-2">+200%</span>
                                            <span class="text-nowrap">from yesterday</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5"
                            style="min-width: 300px;">
                                <div class="card card-stats">
                                    <div class="card-body" style="background-color: #f1f1f1; border-radius: 10px;">
                                        <div class="row">
                                            <div class="col">
                                                <p class="h5 card-title text-uppercase mb-0" style="color: black; font-weight: bold;">T-Shirt Sold Today</p>
                                                <span class="h2 mb-0" style="color: black;">9</span>
                                            </div>
                                            <div class="col-auto">
                                                <div class="icon icon-shape bg-dark text-white rounded-circle shadow"
                                                style="width: 50px; height: 50px;
                                                display: flex;
                                                align-items: center;
                                                justify-content: center;">
                                                    👕
                                                </div>
                                            </div>
                                        </div>
                                        <p class="mt-3 mb-0 text-muted text-sm">
                                            <span class="text-danger me-2">-60%</span>
                                            <span class="text-nowrap">from yesterday</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5"
                            style="min-width: 300px;">
                                <div class="card card-stats">
                                    <div class="card-body" style="background-color: #f1f1f1; border-radius: 10px;">
                                        <div class="row">
                                            <div class="col">
                                                <p class="h5 card-title text-uppercase mb-0" style="color: black; font-weight: bold;">Pants Sold Today</p>
                                                <span class="h2 mb-0" style="color: black;">6</span>
                                            </div>
                                            <div class="col-auto">
                                                <div class="icon icon-shape bg-dark text-white rounded-circle shadow"
                                                style="width: 50px; height: 50px;
                                                display: flex;
                                                align-items: center;
                                                justify-content: center;">
                                                    👖
                                                </div>
                                            </div>
                                        </div>
                                        <p class="mt-3 mb-0 text-muted text-sm">
                                            <span class="text-success me-2">+20%</span>
                                            <span class="text-nowrap">from yesterday</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ==================== USERS ==================== -->
                <div class="tab-pane fade" id="users" role="tabpanel" style="margin-left: 70px;">
                    <h1 class="text-center mt-5">Users</h1>
                    <p class="text-center">Here is the users management section.</p>
                    <ul class="nav nav-tabs" id="usersMainTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="users-maininfo-tab" data-bs-toggle="tab" data-bs-target="#users-maininfo" type="button" role="tab">Main Info</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="users-list-tab" data-bs-toggle="tab" data-bs-target="#users-usersList" type="button" role="tab">Users List</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="usersMainTabContent">
                        <!-- Main Tab -->
                        <div class="tab-pane fade show active" id="users-maininfo" role="tabpanel" aria-labelledby="users-maininfo-tab">
                            <h1 class="mt-5">Users 👥</h1>
                            <button class="btn btn-outline-secondary mb-3" data-bs-toggle="collapse" data-bs-target="#userscontent">
                                View Details
                            </button>
                            <div id="userscontent" class="collapse">
                                <div class="row" style="display: flex; flex-wrap: wrap; gap: 20px;">
                                    <div class="col-md-5"
                                    style="min-width: 300px;">
                                        <div class="card card-stats">
                                            <div class="card-body" style="background-color: #f1f1f1; border-radius: 10px;">
                                                <div class="row">
                                                    <div class="col">
                                                        <h5 class="card-title text-uppercase mb-0" style="color: black;">Total Registered Users</h5>
                                                        <span class="h2 font-weight-bold mb-0" style="color: black;">110</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="icon icon-shape bg-dark text-white rounded-circle shadow"
                                                        style="width: 50px; height: 50px;
                                                        display: flex;
                                                        align-items: center;
                                                        justify-content: center;">
                                                            👥
                                                        </div>
                                                    </div>
                                                </div>
                                                <p class="mt-3 mb-0 text-muted text-sm">
                                                    <span class="text-success me-2">+200%</span>
                                                    <span class="text-nowrap">from yesterday</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5"
                                    style="min-width: 300px;">
                                        <div class="card card-stats">
                                            <div class="card-body" style="background-color: #f1f1f1; border-radius: 10px;">
                                                <div class="row">
                                                    <div class="col">
                                                        <h5 class="card-title text-uppercase mb-0" style="color: black;">Users Registered Today</h5>
                                                        <span class="h2 font-weight-bold mb-0" style="color: black;">45</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="icon icon-shape bg-dark text-white rounded-circle shadow"
                                                        style="width: 50px; height: 50px;
                                                        display: flex;
                                                        align-items: center;
                                                        justify-content: center;">
                                                            👤
                                                        </div>
                                                    </div>
                                                </div>
                                                <p class="mt-3 mb-0 text-muted text-sm">
                                                    <span class="text-success me-2">+42%</span>
                                                    <span class="text-nowrap">from yesterday</span>
                                                </p>
                                                <!-- colocar uma barra para dizer o maximo de registrados por dia-->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5"
                                    style="min-width: 300px;">
                                        <div class="card card-stats">
                                            <div class="card-body" style="background-color: #f1f1f1; border-radius: 10px;">
                                                <div class="row">
                                                    <div class="col">
                                                        <h5 class="card-title text-uppercase mb-0" style="color: black;">Active Accounts</h5>
                                                        <span class="h2 font-weight-bold mb-0" style="color: black;">101</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="icon icon-shape bg-dark text-white rounded-circle shadow"
                                                        style="width: 50px; height: 50px;
                                                        display: flex;
                                                        align-items: center;
                                                        justify-content: center;">
                                                            ✔
                                                        </div>
                                                    </div>
                                                </div>
                                                <p class="mt-3 mb-0 text-muted text-sm">
                                                    <span class="text-success me-2">+20%</span>
                                                    <span class="text-nowrap">from yesterday</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5"
                                    style="min-width: 300px;">
                                        <div class="card card-stats">
                                            <div class="card-body" style="background-color: #f1f1f1; border-radius: 10px;">
                                                <div class="row">
                                                    <div class="col">
                                                        <h5 class="card-title text-uppercase mb-0" style="color: black;">Inactive Accounts</h5>
                                                        <span class="h2 font-weight-bold mb-0" style="color: black;">9</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="icon icon-shape bg-dark text-white rounded-circle shadow"
                                                        style="width: 50px; height: 50px;
                                                        display: flex;
                                                        align-items: center;
                                                        justify-content: center;">
                                                            😴
                                                        </div>
                                                    </div>
                                                </div>
                                                <p class="mt-3 mb-0 text-muted text-sm">
                                                    <span class="text-danger me-2">-60%</span>
                                                    <span class="text-nowrap">from yesterday</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                            <div class="mt-4"> 
                                <section class="mb-4">
                                    <h3 class="mb-3">Deleted Accounts ❌</h3>
                                    <button class="btn btn-outline-secondary mb-3" data-bs-toggle="offcanvas" data-bs-target="#offcanvasStart">
                                        View Details
                                    </button>
                                </section>
                                <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasStart">
                                    <div class="offcanvas-header">
                                        <h5 class="offcanvas-title">Deleted Accounts Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
                                    </div>
                                    <div class="offcanvas-body">
                                        <div class="col-md-5"
                                        style="min-width: 300px;">
                                            <div class="card card-stats">
                                                <div class="card-body" style="background-color: #f1f1f1; border-radius: 10px;">
                                                    <div class="row">
                                                        <div class="col">
                                                            <h5 class="card-title text-uppercase mb-0" style="color: black;">Deleted Accounts</h5>
                                                            <span class="h2 font-weight-bold mb-0" style="color: black;">6</span>
                                                        </div>
                                                        <div class="col-auto">
                                                            <div class="icon icon-shape bg-dark text-white rounded-circle shadow"
                                                            style="width: 50px; height: 50px;
                                                            display: flex;
                                                            align-items: center;
                                                            justify-content: center;">
                                                                ❌
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p class="mt-3 mb-0 text-muted text-sm">
                                                        <span class="text-success me-2">+20%</span>
                                                        <span class="text-nowrap">from yesterday</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <h3 class="mb-3">Users Registered Today 🕓</h3>
                                <div class="col-md-5"
                                    style="min-width: 300px;">
                                        <div class="card card-stats">
                                            <div class="card-body" style="background-color: #f1f1f1; border-radius: 10px;">
                                                <div class="row">
                                                    <div class="col">
                                                        <h5 class="card-title text-uppercase mb-0" style="color: black;">Users Registered Today</h5>
                                                        <span class="h2 font-weight-bold mb-0" style="color: black;">45</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="icon icon-shape bg-dark text-white rounded-circle shadow"
                                                        style="width: 50px; height: 50px;
                                                        display: flex;
                                                        align-items: center;
                                                        justify-content: center;">
                                                            👤
                                                        </div>
                                                    </div>
                                                </div>
                                                <p class="mt-3 mb-0 text-muted text-sm">
                                                    <span class="text-success me-2">+42%</span>
                                                    <span class="text-nowrap">from yesterday</span>
                                                </p>
                                                <!-- colocar uma barra para dizer o maximo de registrados por dia-->
                                                <div class="progress mt-2" role="progressbar" aria-label="Users Today" aria-valuenow="45" aria-valuemin="0" aria-valuemax="60">
                                                    <div class="progress-bar" style="width: 75%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <button class="btn btn-outline-secondary mt-3" data-bs-toggle="collapse" data-bs-target="#userstodaycontent">
                                    View Details
                                </button>
                                <div id="userstodaycontent" class="collapse mt-3">
                                    <div class="row" style="display: flex; flex-wrap: wrap; gap: 20px;">
                                        <div class="col-md-5"
                                        style="min-width: 300px;">
                                            <div class="card card-stats">
                                                <div class="card-body" style="background-color: #f1f1f1; border-radius: 10px;">
                                                    <div class="row">
                                                        <div class="col">
                                                            <h5 class="card-title text-uppercase mb-0" style="color: black;">Total Registered Users</h5>
                                                            <span class="h2 font-weight-bold mb-0" style="color: black;">110</span>
                                                        </div>
                                                        <div class="col-auto">
                                                            <div class="icon icon-shape bg-dark text-white rounded-circle shadow"
                                                            style="width: 50px; height: 50px;
                                                            display: flex;
                                                            align-items: center;
                                                            justify-content: center;">
                                                                👥
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p class="mt-3 mb-0 text-muted text-sm">
                                                        <span class="text-success me-2">+200%</span>
                                                        <span class="text-nowrap">from yesterday</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5"
                                        style="min-width: 300px;">
                                            <div class="card card-stats">
                                                <div class="card-body" style="background-color: #f1f1f1; border-radius: 10px;">
                                                    <div class="row">
                                                        <div class="col">
                                                            <h5 class="card-title text-uppercase mb-0" style="color: black;">Users Registered Today</h5>
                                                            <span class="h2 font-weight-bold mb-0" style="color: black;">45</span>
                                                        </div>
                                                        <div class="col-auto">
                                                            <div class="icon icon-shape bg-dark text-white rounded-circle shadow"
                                                            style="width: 50px; height: 50px;
                                                            display: flex;
                                                            align-items: center;
                                                            justify-content: center;">
                                                                👤
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p class="mt-3 mb-0 text-muted text-sm">
                                                        <span class="text-success me-2">+42%</span>
                                                        <span class="text-nowrap">from yesterday</span>
                                                    </p>
                                                    <!-- colocar uma barra para dizer o maximo de registrados por dia-->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5"
                                        style="min-width: 300px;">
                                            <div class="card card-stats">
                                                <div class="card-body" style="background-color: #f1f1f1; border-radius: 10px;">
                                                    <div class="row">
                                                        <div class="col">
                                                            <h5 class="card-title text-uppercase mb-0" style="color: black;">Active Accounts</h5>
                                                            <span class="h2 font-weight-bold mb-0" style="color: black;">101</span>
                                                        </div>
                                                        <div class="col-auto">
                                                            <div class="icon icon-shape bg-dark text-white rounded-circle shadow"
                                                            style="width: 50px; height: 50px;
                                                            display: flex;
                                                            align-items: center;
                                                            justify-content: center;">
                                                                ✔
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p class="mt-3 mb-0 text-muted text-sm">
                                                        <span class="text-success me-2">+20%</span>
                                                        <span class="text-nowrap">from yesterday</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5"
                                        style="min-width: 300px;">
                                            <div class="card card-stats">
                                                <div class="card-body" style="background-color: #f1f1f1; border-radius: 10px;">
                                                    <div class="row">
                                                        <div class="col">
                                                            <h5 class="card-title text-uppercase mb-0" style="color: black;">Inactive Accounts</h5>
                                                            <span class="h2 font-weight-bold mb-0" style="color: black;">9</span>
                                                        </div>
                                                        <div class="col-auto">
                                                            <div class="icon icon-shape bg-dark text-white rounded-circle shadow"
                                                            style="width: 50px; height: 50px;
                                                            display: flex;
                                                            align-items: center;
                                                            justify-content: center;">
                                                                😴
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p class="mt-3 mb-0 text-muted text-sm">
                                                        <span class="text-danger me-2">-60%</span>
                                                        <span class="text-nowrap">from yesterday</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                            <h1 class="mt-5">Retention Rate</h1>
                            <div class="row" style="display: flex; flex-wrap: wrap; gap: 20px;">
                                <div class="col-md-5" 
                                style="min-width: 300px;">
                                    <div class="card card-stats">
                                        <div class="card-body" style="background-color: #f1f1f1; border-radius: 10px;">
                                            <div class="row">
                                                <div class="col">
                                                    <span class="h2 font-weight-bold mb-0" style="color: black;">110</span>
                                                    <p class="card-title text-uppercase mb-0" style="color: black;">Total Registered Users</p>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="icon icon-shape bg-dark text-white rounded-circle shadow"
                                                    style="width: 50px; height: 50px;
                                                    display: flex;
                                                    align-items: center;
                                                    justify-content: center;">
                                                        👥
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col-3">
                                                    <canvas id="donutChart" width="200" height="200"></canvas>
                                                </div>
                                                <div class="col-9">
                                                    <div class="row">
                                                        <div class="col">
                                                            <p class="card-title text-uppercase mb-0" style="color: black; font-size: 12px; font-weight: bold;">Active Accounts</p>
                                                        </div>
                                                        <div class="col-auto">
                                                            <span class="h2 font-weight-bold mb-0" style="color: black; font-size: 30px;">101</span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col">
                                                            <p class="card-title text-uppercase mb-0" style="color: black; font-size: 12px; font-weight: bold;">Inactive Accounts</p>
                                                        </div>
                                                        <div class="col-auto">
                                                            <span class="h2 font-weight-bold mb-0" style="color: black; font-size: 30px;">9</span>
                                                        </div>  
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="mt-3 mb-0 text-muted text-sm">
                                                <span class="text-danger me-2">-60%</span>
                                                <span class="text-nowrap">from yesterday</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5"
                                 style="min-width: 300px;">
                                    <div class="card card-stats">
                                        <div class="card-body" style="background-color: #f1f1f1; border-radius: 10px;">
                                            <div class="row">
                                                <div class="col">
                                                    <h5 class="card-title text-uppercase mb-0" style="color: black;">Deleted Accounts</h5>
                                                    <span class="h2 font-weight-bold mb-0" style="color: black;">6</span>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="icon icon-shape bg-dark text-white rounded-circle shadow"
                                                    style="width: 50px; height: 50px;
                                                    display: flex;
                                                    align-items: center;
                                                    justify-content: center;">
                                                        ❌
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="mt-3 mb-0 text-muted text-sm">
                                                <span class="text-success me-2">+20%</span>
                                                <span class="text-nowrap">from yesterday</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <div class="card card-stats mt-3">
                                    <div class="card-body" style="background-color: #f1f1f1; border-radius: 10px;">
                                        <div class="row">
                                            <div class="col">
                                                <ul class="nav nav-tabs" id="retentionTab" role="tablist">
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link active" id="retention-rate-tab" data-bs-toggle="tab" data-bs-target="#retention-rate" type="button" role="tab">Retention</button>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link" id="retention-inactive-tab" data-bs-toggle="tab" data-bs-target="#retention-inactive" type="button" role="tab">Inactive</button>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link" id="retention-deleted-tab" data-bs-toggle="tab" data-bs-target="#retention-deleted" type="button" role="tab">Deleted</button>
                                                    </li>
                                                </ul>
                                                <div class="tab-content" id="retentionTabContent">
                                                    <div class="tab-pane fade show active" id="retention-rate" role="tabpanel" aria-labelledby="retention-rate-tab">
                                                        <table class="table mt-3">
                                                            <thead>
                                                                <tr>
                                                                    <th>Retention Rate</th>
                                                                    <th>Date</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>85%</td>
                                                                    <td>2024-01-15</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>85%</td>
                                                                    <td>2024-01-15</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>               
                                                    <div class="tab-pane fade" id="retention-inactive" role="tabpanel" aria-labelledby="retention-inactive-tab">
                                                        <table class="table mt-3">
                                                            <thead>
                                                                <tr>
                                                                    <th>Retention Rate</th>
                                                                    <th>Date</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>40%</td>
                                                                    <td>2024-01-15</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>40%</td>
                                                                    <td>2024-01-15</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="tab-pane fade" id="retention-deleted" role="tabpanel" aria-labelledby="retention-deleted-tab">
                                                        <table class="table mt-3">
                                                            <thead>
                                                                <tr>
                                                                    <th>Retention Rate</th>
                                                                    <th>Date</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>25%</td>
                                                                    <td>2024-01-15</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>25%</td>
                                                                    <td>2024-01-15</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5" style="width: 450px;">
                                <div class="card card-stats">
                                    <div class="card-body" style="background-color: #f1f1f1; border-radius: 10px;">
                                        <div class="row">
                                            <div class="col">
                                               <p class="h2" style="font-weight: bold;">Inactive Users by Period</p>
                                            </div>
                                        </div>
                                        <div class="my-3" style="width: 400px;">
                                            <canvas id="chart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5">
                                <p class="h2 mt-5" style="font-weight: bold;">Reasons for Account Deletion</p>
                                <div class="card card-stats mt-3">
                                    <div class="card-body" style="background-color: #f1f1f1; border-radius: 10px;">
                                        <div class="row">
                                            <p class="h3 mb-3">Feedbacks: 4</p>
                                            <div class="col">
                                                <div class="overflow-auto" style="height: 182px;">
                                                    <div class="accordion" id="clientsAccordion">
                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header">
                                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                                                    Clients Name - Title - date/date/date
                                                                </button>
                                                            </h2>
                                                            <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#clientsAccordion">
                                                                <div class="accordion-body">
                                                                    <strong>This is the first item's accordion body.</strong> It is shown by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header">
                                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                                    Clients Name - Title - date/date/date
                                                                </button>
                                                            </h2>
                                                            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#clientsAccordion">
                                                                <div class="accordion-body">
                                                                    <strong>This is the second item's accordion body.</strong> It is shown by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Users List -->
                        <div class="tab-pane fade" id="users-usersList" role="tabpanel" aria-labelledby="users-list-tab">
                            <div class="mt-3">
                                <div class="card card-stats mt-3">
                                    <div class="card-header">
                                        <ul class="nav nav-tabs" id="usersListTab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="usersList-insert-tab" data-bs-toggle="tab" data-bs-target="#usersList-insert" type="button" role="tab">Insert</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="usersList-search-tab" data-bs-toggle="tab" data-bs-target="#usersList-search" type="button" role="tab">Search</button>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="card-body" style="background-color: #f1f1f1; border-radius: 10px;">
                                        <div class="row">
                                            <div class="col">
                                                <div class="tab-content" id="usersListTabContent">
                                                    <div class="tab-pane fade show active" id="usersList-insert" role="tabpanel" aria-labelledby="usersList-insert-tab">
                                                        <!-- Botão que abre o modal -->
                                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editUserModal">
                                                          Editar perfil
                                                        </button>
                                                    </div>
                                                    <!-- Barra de pesquisa e filtros -->
                                                    <div class="tab-pane fade" id="usersList-search" role="tabpanel" aria-labelledby="usersList-search-tab">
                                                        <div class="card mb-3">
                                                          <div class="card-body">
                                                            <div class="row g-2">
                                                              <div class="col-md-10">
                                                                <input type="text" id="searchUsersInput" class="form-control" placeholder="🔍 Search Users...">
                                                              </div>
                                                                <div class="col-md-2">
                                                                    <button class="btn btn-primary mb-3 w-100" id="btnLimparFiltrosUsers">
                                                                        Clear
                                                                    </button>   
                                                                </div> 
                                                              <div class="col-md-3">
                                                                <label class="form-label">Role</label>
                                                                <select id="filterRole" class="form-select">
                                                                    <option value="">All</option>
                                                                    <option value="Admin">Admin</option>
                                                                    <option value="User">User</option>
                                                                </select>
                                                              </div>
                                                              <div class="col-md-3">
                                                                <label class="form-label">Status</label>
                                                                <select id="filterStatus" class="form-select">
                                                                    <option value="">All</option>
                                                                    <option value="Active">Active</option>
                                                                    <option value="Inactive">Inactive</option>
                                                                    <option value="Suspended">Suspended</option>
                                                                    <option value="Deleted">Deleted</option>
                                                                    <option value="Banned">Banned</option>
                                                                </select>
                                                              </div>
                                                              <div class="col-md-6">
                                                                <label class="form-label">Account Creation Date</label>
                                                                <div class="d-flex gap-1">
                                                                  <input type="date" id="filterAccountCreationDateMin" class="form-control">
                                                                  <input type="date" id="filterAccountCreationDateMax" class="form-control">
                                                                </div>
                                                              </div>
                                                            </div>
                                                          </div>
                                                        </div>
                                                    </div>

                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h5 class="card-title mb-0">Users</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="table-responsive">
                                                                <table class="table table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <?php foreach($camposvisiveis as $campo): ?>
                                                                                <th><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $campo))) ?></th>
                                                                            <?php endforeach; ?>
                                                                            <th>Actions</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="corpoTabelaUsersDB">
                                                                        <?php foreach($users as $row): ?>
                                                                        <tr>
                                                                            <?php foreach($camposvisiveis as $campo): ?>
                                                                                <?php if ($campo === 'is_admin'): ?>
                                                                                    <td><?= $row[$campo] == 1 ? 'Admin' : 'User' ?></td>
                                                                                    <?php elseif (in_array($campo, $camposEditaveis)): ?>
                                                                                        <td data-campo="<?= htmlspecialchars($campo) ?>"
                                                                                            data-id="<?= (int)$row['id'] ?>">
                                                                                            <?= htmlspecialchars($row[$campo]) ?>
                                                                                        </td>
                                                                                <?php else: ?>
                                                                                    <td><?= htmlspecialchars($row[$campo]) ?></td>
                                                                                <?php endif; ?>
                                                                            <?php endforeach; ?>
                                                                            <td>
                                                                                <div class="row">
                                                                                    <div class="col-md-6 d-flex">
                                                                                        <button class="btn btn-sm btn-warning btnUpdateUser" data-bs-toggle="modal" data-bs-target="#editUserModal-<?= $row['id'] ?>" style="width: 80px;"> Update </button>
                                                                                    </div>
                                                                                    <div class="col-md-6 d-flex">
                                                                                        <button class="btn btn-sm btn-danger btnDeleteUser" style="width: 80px;">Delete</button>
                                                                                    </div>
                                                                                    <div class="col-md-6 d-flex">
                                                                                        <button class="btn btn-sm btn-primary btnActivateUser" style="width: 80px;">Activate</button>
                                                                                    </div>
                                                                                    <div class="col-md-6 d-flex">
                                                                                        <button class="btn btn-sm btn-danger btnSuspendUser" style="width: 80px;">Suspend</button>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <?php endforeach; ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ==================== FINANCE ==================== -->
                <div class="tab-pane fade" id="finance" role="tabpanel" style="margin-left: 70px;">
                    <h1 class="text-center mt-5">Finance</h1>
                    <h3>Revenue - Receita</h3>
                    <div class="row">
                        <div class="col-md-5"
                            style="min-width: 300px;">
                            <div class="card card-stats">
                                <div class="card-body" style="background-color: #f1f1f1; border-radius: 10px;">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase mb-0" style="color: black;">Revenue</h5>
                                            <span class="h2 font-weight-bold mb-0" style="color: black;">10,000€</span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-dark text-white rounded-circle shadow"
                                            style="width: 50px; height: 50px;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;">
                                                💰
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-3 mb-0 text-muted text-sm">
                                        <span class="text-success me-2">+20%</span>
                                        <span class="text-nowrap">from yesterday</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h3>Sales - Vendas</h3>
                    <h3>Profit - Lucro</h3>
                    <h3>Expenses - Despesas</h3>
                    <h3>Product Manufacturing Cost - Custo de fabricação do produto</h3>
                    <h3>Average Order Value - Ticket Medio</h3>
                    <h3>Claimed Coupons - Cupons Resgatados</h3>
                    <h3>Impact of discounts on revenue - Impacto dos descontos na receita</h3>
                </div>

                <!-- ==================== PRODUCTS ==================== -->
                <div class="tab-pane fade" id="products" role="tabpanel" style="margin-left: 70px;">
                    <h1 class="text-center mt-5">Products</h1>
                    <p class="text-center">Here is the products management section.</p>
                    <ul class="nav nav-tabs" id="productsMainTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="products-maininfo-tab" data-bs-toggle="tab" data-bs-target="#products-maininfo" type="button" role="tab">Main Info</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="products-list-tab" data-bs-toggle="tab" data-bs-target="#products-productsList" type="button" role="tab">Products List</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="productsMainTabContent">
                        <!-- Main Tab -->
                        <div class="tab-pane fade show active" id="products-maininfo" role="tabpanel" aria-labelledby="products-maininfo-tab">
                            <div class="d-flex justify-content-center mt-4">
                                <div class="row" style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; max-width: 950px;">
                                    <!-- Top Selling Products Table -->
                                    <div class="col-md-12" style="min-width: 300px;">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title mb-0">Top Selling Products</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>Image</th>
                                                                <th>Color</th>
                                                                <th>Product</th>
                                                                <th>Category</th>
                                                                <th>Price</th>
                                                                <th>Sales</th>
                                                                <th>Revenue</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><img src="Images/productImage1.jpg" alt="product image" style="width: 40px;"></td>
                                                                <!-- Color -->
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        <div style="width: 40px; height: 40px; background-color: #000; margin-right: 1px;"></div>
                                                                    </div>
                                                                </td>
                                                                <td><span>Agitat Solum Merch</span></td>
                                                                <td>T-Shirts</td>
                                                                <td>€15.00</td>
                                                                <td>47</td>
                                                                <td>€705.00</td>
                                                            </tr>
                                                            <tr>
                                                                <td><img src="Images/productImage1.jpg" alt="product image" style="width: 40px;"></td>
                                                                <!-- Color -->
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        <div style="width: 40px; height: 40px; background-color: #333; margin-right: 1px;"></div>
                                                                    </div>
                                                                </td>
                                                                <td><span>Custom Baggy Jeans</span></td>
                                                                <td>Pants</td>
                                                                <td>€85.00</td>
                                                                <td>32</td>
                                                                <td>€2,720.00</td>
                                                            </tr>
                                                            <tr>
                                                                <td><img src="Images/productImage1.jpg" alt="product image" style="width: 40px;"></td>
                                                                <!-- Color -->
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        <div style="width: 40px; height: 40px; background-color: #555; margin-right: 1px;"></div>
                                                                    </div>
                                                                </td>
                                                                <td><span>Battle Jacket</span></td>
                                                                <td>Jackets</td>
                                                                <td>€105.00</td>
                                                                <td>18</td>
                                                                <td>€1,890.00</td>
                                                            </tr>
                                                            <tr>
                                                                <td><img src="Images/productImage1.jpg" alt="product image" style="width: 40px;"></td>
                                                                <!-- Color -->
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        <div style="width: 40px; height: 40px; background-color: #222; margin-right: 1px;"></div>
                                                                    </div>
                                                                </td>
                                                                <td><span>Custom T-Shirt</span></td>
                                                                <td>T-Shirts</td>
                                                                <td>€25.00</td>
                                                                <td>28</td>
                                                                <td>€700.00</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Products List Tab -->                       
                        <div class="tab-pane fade" id="products-productsList" role="tabpanel" aria-labelledby="products-list-tab">
                            <div class="mt-3">
                                <div class="card card-stats mt-3">
                                    <div class="card-header">
                                        <ul class="nav nav-tabs" id="productsListTab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="productsList-insert-tab" data-bs-toggle="tab" data-bs-target="#productsList-insert" type="button" role="tab">Insert</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="productsList-search-tab" data-bs-toggle="tab" data-bs-target="#productsList-search" type="button" role="tab">Search</button>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="card-body" style="background-color: #f1f1f1; border-radius: 10px;">
                                        <div class="row">
                                            <div class="col">
                                                <div class="tab-content" id="productsListTabContent">
                                                    <div class="tab-pane fade show active" id="productsList-insert" role="tabpanel" aria-labelledby="productsList-insert-tab">
                                                        <!-- Botão para abrir o modal -->
                                                        <button id="addProduct" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#insertProductModal">
                                                            Add Product
                                                        </button>       
                                                    </div>
                                                    <div class="tab-pane fade" id="productsList-search" role="tabpanel" aria-labelledby="productsList-search-tab">
                                                        <!-- Barra de pesquisa e filtros -->
                                                        <div class="card mb-3">
                                                          <div class="card-body">
                                                            <div class="row g-2">
                                                              <div class="col-md-10">
                                                                <input type="text" id="searchProduct" class="form-control" placeholder="🔍 Search Products...">
                                                              </div>
                                                                <div class="col-md-2">
                                                                    <button class="btn btn-primary mb-3 w-100" id="btnLimparFiltros">
                                                                        Clear
                                                                    </button>   
                                                                </div> 
                                                              <div class="col-md-3">
                                                                <label class="form-label">Category</label>
                                                                <select id="filterCategory" class="form-select">
                                                                  <option value="">All</option>
                                                                  <option value="T-Shirts">T-Shirts</option>
                                                                  <option value="Pants">Pants</option>
                                                                  <option value="Jackets">Jackets</option>
                                                                </select>
                                                              </div>
                                                              <div class="col-md-3">
                                                                <label class="form-label">Price (€)</label>
                                                                <div class="d-flex gap-1">
                                                                  <input type="text" id="filterPriceMin" class="form-control" placeholder="Min">
                                                                  <input type="text" id="filterPriceMax" class="form-control" placeholder="Max">
                                                                </div>
                                                              </div>
                                                              <div class="col-md-3">
                                                                <label class="form-label">Sales</label>
                                                                <div class="d-flex gap-1">
                                                                  <input type="text" id="filterSalesMin" class="form-control" placeholder="Min">
                                                                  <input type="text" id="filterSalesMax" class="form-control" placeholder="Max">
                                                                </div>
                                                              </div>
                                                              <div class="col-md-3">
                                                                <label class="form-label">Revenue (€)</label>
                                                                <div class="d-flex gap-1">
                                                                  <input type="text" id="filterRevenueMin" class="form-control" placeholder="Min">
                                                                  <input type="text" id="filterRevenueMax" class="form-control" placeholder="Max">
                                                                </div>
                                                              </div>
                                                            </div>
                                                          </div>
                                                        </div>
                                                    </div>
                                                    <!-- Tabela -->
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Image</th>
                                                                <th>Color</th>
                                                                <th>Product</th>
                                                                <th>Category</th>
                                                                <th>Size</th>
                                                                <th>Price</th>
                                                                <th>Manufac. Cost</th>
                                                                <th>Stock</th>
                                                                <th>Sales</th>
                                                                <th>Revenue</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="corpoTabelaProduct">
                                                        </tbody>
                                                    </table>

                                                     <div class="card">
                                                        <div class="card-header">
                                                            <h5 class="card-title mb-0">Users</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="table-responsive">
                                                                <table class="table table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <?php foreach($camposvisiveisProdutos as $campo): ?>
                                                                                <th><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $campo))) ?></th>
                                                                            <?php endforeach; ?>
                                                                            <th>Actions</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="corpoTabelaProductsDB">
                                                                        <?php foreach($products as $row): ?>
                                                                        <tr>
                                                                            <?php foreach($camposvisiveisProdutos as $campo): 
                                                                                    $sales = $productDAO->productsSales($row['id']);
                                                                                    $revenue = $productDAO->productsRevenue($row['id']); ?> 
                                                                                <?php if ($campo === 'sales'): ?>
                                                                                    <td><?= htmlspecialchars((string)$sales) ?></td>
                                                                                <?php elseif ($campo === 'revenue'): ?>
                                                                                    <td><?= htmlspecialchars((string)$revenue) ?></td>
                                                                                <?php elseif(in_array($campo, $camposEditaveisProdutos)): ?>
                                                                                    <td data-campo="<?= htmlspecialchars($campo) ?>"
                                                                                        data-id="<?= (int)$row['id'] ?>">
                                                                                        <?= htmlspecialchars($row[$campo]) ?>
                                                                                    </td>  
                                                                                <?php else: ?>
                                                                                    <td><?= htmlspecialchars($row[$campo]) ?></td>
                                                                                <?php endif; ?>
                                                                            <?php endforeach; ?>
                                                                            <td>
                                                                                <div class="row">
                                                                                    <div class="col-md-6 d-flex">
                                                                                        <button class="btn btn-sm btn-warning btnUpdateUser" data-bs-toggle="modal" data-bs-target="#editUserModal-<?= $row['id'] ?>" style="width: 80px;"> Update </button>
                                                                                    </div>
                                                                                    <div class="col-md-6 d-flex">
                                                                                        <button class="btn btn-sm btn-danger btnDeleteUser" style="width: 80px;">Delete</button>
                                                                                    </div>
                                                                                    <div class="col-md-6 d-flex">
                                                                                        <button class="btn btn-sm btn-primary btnActivateUser" style="width: 80px;">Activate</button>
                                                                                    </div>
                                                                                    <div class="col-md-6 d-flex">
                                                                                        <button class="btn btn-sm btn-danger btnSuspendUser" style="width: 80px;">Suspend</button>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <?php endforeach; ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ==================== ORDERS ==================== -->
                <div class="tab-pane fade" id="orders" role="tabpanel" style="margin-left: 70px">
                    <h1 class="text-center mt-5">Orders</h1>
                    <p class="text-center">Here you can manage the site orders.</p>
                </div>

                <!-- ==================== COMUNITY ==================== -->
                <div class="tab-pane fade" id="comunity" role="tabpanel" style="margin-left: 70px;">
                    <h1 class="text-center mt-5">Comunity</h1>
                    <p class="text-center">Here is the comunity management section.</p>
                    <ul class="nav nav-tabs" id="comunityMainTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="comunity-maininfo-tab" data-bs-toggle="tab" data-bs-target="#comunity-maininfo" type="button" role="tab">Main Info</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="comunity-list-tab" data-bs-toggle="tab" data-bs-target="#comunity-postList" type="button" role="tab">Post List</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="comunityMainTabContent">
                        <!-- Main Tab -->
                        <div class="tab-pane fade show active" id="comunity-maininfo" role="tabpanel" aria-labelledby="comunity-maininfo-tab">

                        </div>
                        <!-- Post List -->
                        <div class="tab-pane fade" id="comunity-postList" role="tabpanel" aria-labelledby="post-list-tab">
                            <div class="mt-3">
                                <div class="card card-stats mt-3">
                                    <div class="card-header">
                                        <ul class="nav nav-tabs" id="postListTab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="postList-insert-tab" data-bs-toggle="tab" data-bs-target="#postList-insert" type="button" role="tab">Insert</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="postList-search-tab" data-bs-toggle="tab" data-bs-target="#postList-search" type="button" role="tab">Search</button>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="card-body" style="background-color: #f1f1f1; border-radius: 10px;">
                                        <div class="row">
                                            <div class="col">
                                                <div class="tab-content" id="postListTabContent">
                                                    <div class="tab-pane fade show active" id="postList-insert" role="tabpanel" aria-labelledby="postList-insert-tab">
                                                        <!-- Botão para abrir o modal -->
                                                        <button id="addPost" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#insertPostModal">
                                                            Add Post
                                                        </button>       
                                                    </div>
                                                    <!-- Barra de pesquisa e filtros -->
                                                    <div class="tab-pane fade" id="postList-search" role="tabpanel" aria-labelledby="postList-search-tab">
                                                        <div class="card mb-3">
                                                            <div class="card-body">
                                                                <div class="row g-2">
                                                                    <div class="col-md-10">
                                                                        <input type="text" id="searchPostsInput" class="form-control" placeholder="🔍 Search Posts...">
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <button class="btn btn-primary mb-3 w-100" id="btnLimparFiltrosPosts">
                                                                            Clear
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Tabela Posts -->
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Image</th>
                                                                <th>Text</th>
                                                                <th>User</th>
                                                                <th>Date</th>
                                                                <th>Likes</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <!-- Estao Aqui Dados de Usuarios de Exemplo-->
                                                        <tbody id="corpoTabelaPosts">

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ==================== MARKETING ==================== -->
                <div class="tab-pane fade" id="marketing" role="tabpanel" style="margin-left: 70px;">
                    <h1 class="text-center mt-5">Marketing</h1>
                    <p class="text-center">Here you can manage the site marketing campaigns.</p>
                </div>
            </div>  
        </div>
        <!-- end-conteudo -->
        
    </div>
    <?php include __DIR__ . "/../includes/footer.php"; ?>
</div>

<!-- Modais -->
<!-- Modal de update users -->
<?php foreach($users as $row): ?>
<div class="modal fade" id="editUserModal-<?= $row['id'] ?>" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editar <?= htmlspecialchars($row['username']) ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <form method="post" id="formEditUser-<?= $row['id'] ?>">
        <div class="modal-body">
          <input type="text" name="username" value="<?= htmlspecialchars($row['username']) ?>" class="form-control mb-2" placeholder="Username" required>
          <input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>" class="form-control mb-2" placeholder="Email" required>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <?php if (AuthMiddlewareWeb::canEditProfile($row['id'])): ?>
            <button type="submit" class="btn btn-primary">Guardar</button>
          <?php endif; ?>
        </div>
    </form>
    </div>
  </div>
</div>
<?php endforeach; ?>

<!-- Modal de update de produto -->
<div class="modal fade" id="insertProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label for="inputImage" class="form-label">Image URL</label>
                <input type="text" id="inputImage" class="form-control mb-2" placeholder="Image URL">
                <label for="inputColor" class="form-label">Color</label>
                <input type="color" id="inputColor" class="form-control form-control-color mb-2">
                <label for="inputProductName" class="form-label">Product</label>
                <input type="text" id="inputProductName" class="form-control mb-2" placeholder="Product">
                <label for="inputCategorySelect" class="form-label">Category</label>
                <!-- Categories -->
                <select id="inputCategorySelect" class="form-select mb-2">
                    <option value="">All</option>
                    <option value="T-Shirts">T-Shirts</option>
                    <option value="Pants">Pants</option>
                    <option value="Jackets">Jackets</option>
                </select>
                <label for="inputPrice" class="form-label">Price (€)</label>
                <input type="text" id="inputPrice" class="form-control mb-2" placeholder="Price (€)">
                <label for="inputSales" class="form-label">Sales</label>
                <input type="text" id="inputSales" class="form-control mb-2" placeholder="Sales">
                <label for="inputRevenue" class="form-label">Revenue (€)</label>
                <input type="text" id="inputRevenue" class="form-control mb-2" placeholder="Revenue (€)">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btnSalvarProduct">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de update de post -->                 
<div class="modal fade" id="insertPostModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label for="post-inputImage" class="form-label">Image URL</label>
                <input type="text" id="post-inputImage" class="form-control mb-2" placeholder="Image URL">
                <label for="post-inputText" class="form-label">Text</label>
                <input type="text" id="post-inputText" class="form-control mb-2" placeholder="Text">
                <label for="post-inputUsername" class="form-label">User</label>
                <input type="text" id="post-inputUsername" class="form-control mb-2" placeholder="Username">
                <label for="post-inputDate" class="form-label">Date</label>
                <input type="date" id="post-inputDate" class="form-control mb-2" placeholder="Date">
                <label for="post-inputLikeCount" class="form-label">Like Count</label>
                <input type="number" id="post-inputLikeCount" class="form-control mb-2" placeholder="Like Count">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btnSalvarPost">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- ============================================================
     Todos os scripts no final do body, após o Bootstrap JS
     ============================================================ -->
<script>
    // ---- Donut Chart (Retention Rate) ----
    const ctxDonut = document.getElementById('donutChart').getContext('2d');
    new Chart(ctxDonut, {
        type: 'doughnut',
        data: {
            labels: ['Active', 'Inactive'],
            datasets: [{
                data: [101, 9],
                backgroundColor: ['#3b82f6', '#ef4444'],
                borderWidth: 0
            }]
        },
        options: {
            cutout: '72%',
            plugins: {
                legend: { display: false },
                tooltip: { enabled: true }
            },
            hover: { mode: null }
        }
    });

    // ---- Bar Chart (Inactive Users by Period) ----
    new Chart(document.getElementById('chart'), {
        type: 'bar',
        data: {
            labels: ['< 30 days', '< 60 days', '< 90 days', '6 months +'],
            datasets: [{
                data: [420, 780, 390, 210],
                backgroundColor: ['#f4c95d', '#f4a541', '#e06c3a', '#c0392b'],
                borderRadius: 4,
            }]
        },
        options: {
            plugins: {
                legend: { display: false },
                tooltip: { enabled: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    /** 
    // ---- Script que adiciona/atualiza user ----
    document.getElementById('btnSalvarUser').addEventListener('click', function () {
        const image = document.getElementById('user-inputImage').value;
        const username = document.getElementById('user-inputUsername').value;
        const role = document.getElementById('user-inputRoleSelect').value;
        const email = document.getElementById('user-inputEmail').value;
        const cellphone = document.getElementById('user-inputCellphone').value;
        const status = document.getElementById('user-inputStatusSelect').value;
        const address = document.getElementById('user-inputAddress').value;
        const birthdate = document.getElementById('user-inputBirthdate').value;
        const pronouns = document.getElementById('user-inputPronouns').value;
        const accountCreation = document.getElementById('user-inputAccountCreation').value;
        const lastLogin = document.getElementById('user-inputLastLogin').value;

        if (!username || !email || !cellphone || !status || !address || !birthdate || !pronouns || !accountCreation || !lastLogin) {
            alert('Fill all fields!');
            return;
        }
    
        const user = new Utilizador(image, username, role, email, cellphone, status, address, birthdate, pronouns, accountCreation, lastLogin);
    
        const novaLinha = `
            <tr>
                <td><img src="${user.image || 'productImage1.jpg'}" alt="user image" style="width: 40px; height: 40px; object-fit: cover;"></td>
                <td>${user.username}</td>
                <td>${user.role}</td>
                <td>${user.email}</td>
                <td>${user.status}</td>
                <td>${user.accountCreation}</td>
                <td>${user.lastLogin}</td>
                <td>
                    <div class="row">
                        <div class="col-md-6 d-flex justify-content-center">
                            <button class="btn btn-sm btn-warning btnUpdateUser" style="width: 80px;">Update</button>
                        </div>
                        <div class="col-md-6 d-flex justify-content-center">
                            <button class="btn btn-sm btn-danger btnDeleteUser" style="width: 80px;">Delete</button>
                        </div>
                        <div class="col-md-6 d-flex justify-content-center">
                            <button class="btn btn-sm btn-primary btnActivateUser" style="width: 80px;">Activate</button>
                        </div>
                        <div class="col-md-6 d-flex justify-content-center">
                            <button class="btn btn-sm btn-danger btnSuspendUser" style="width: 80px;">Suspend</button>
                        </div>
                    </div>
                </td>
            </tr>`;
        
        const editIndex = this.dataset.editLinha;
        const tbody = document.getElementById('corpoTabelaUsers');
        
        if (editIndex !== undefined && editIndex !== '') {
            tbody.rows[editIndex].outerHTML = novaLinha;
            delete this.dataset.editLinha;
        } else {
            tbody.innerHTML += novaLinha;
        }
    
        document.getElementById('user-inputImage').value = '';
        document.getElementById('user-inputUsername').value = '';
        document.getElementById('user-inputRoleSelect').value = 'Admin';
        document.getElementById('user-inputEmail').value = '';
        document.getElementById('user-inputCellphone').value = '';
        document.getElementById('user-inputStatusSelect').value = 'Active';
        document.getElementById('user-inputAddress').value = '';
        document.getElementById('user-inputBirthdate').value = '';
        document.getElementById('user-inputPronouns').value = '';
        document.getElementById('user-inputAccountCreation').value = '';
        document.getElementById('user-inputLastLogin').value = '';
    
        bootstrap.Modal.getOrCreateInstance(document.getElementById('insertUserModal')).hide();
    });

    // ---- Script de UPDATE e DELETE de Users ----
    document.getElementById('corpoTabelaUsers').addEventListener('click', function(e) {
        const linha = e.target.closest('tr');

        // DELETE
        if (e.target.classList.contains('btnDeleteUser')) {
            linha.remove();
        }
    
        // UPDATE
        if (e.target.classList.contains('btnUpdateUser')) {
            const cells = linha.querySelectorAll('td');
        
            document.getElementById('user-inputImage').value = cells[0].querySelector('img').src;
            document.getElementById('user-inputUsername').value = cells[1].textContent;
            document.getElementById('user-inputRoleSelect').value = cells[2].textContent;
            document.getElementById('user-inputEmail').value = cells[3].textContent;
            document.getElementById('user-inputStatusSelect').value = cells[4].textContent.trim();
            document.getElementById('user-inputAccountCreation').value = cells[5].textContent;
            document.getElementById('user-inputLastLogin').value = cells[6].textContent;
        
            document.getElementById('btnSalvarUser').dataset.editLinha = Array.from(
                document.getElementById('corpoTabelaUsers').rows
            ).indexOf(linha);
        
            bootstrap.Modal.getOrCreateInstance(document.getElementById('insertUserModal')).show();
        }

            // ACTIVATE
            if (e.target.classList.contains('btnActivateUser')) {
                linha.cells[4].textContent = 'Active';
            }
    
            // SUSPEND
        if (e.target.classList.contains('btnSuspendUser')) {
            linha.cells[4].textContent = 'Suspended';
        }
    });

    document.getElementById('insertUserModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('btnSalvarUser').dataset.editLinha = '';
        document.getElementById('user-inputImage').value = '';
        document.getElementById('user-inputUsername').value = '';
        document.getElementById('user-inputRoleSelect').value = 'Admin';
        document.getElementById('user-inputEmail').value = '';
        document.getElementById('user-inputCellphone').value = '';
        document.getElementById('user-inputStatusSelect').value = 'Active';
        document.getElementById('user-inputAddress').value = '';
        document.getElementById('user-inputBirthdate').value = '';
        document.getElementById('user-inputPronouns').value = '';
        document.getElementById('user-inputAccountCreation').value = '';
        document.getElementById('user-inputLastLogin').value = '';
    });
*/
    // ---- Script de filtragem de Users ----
    function filtrarTabelaUsers() {
        const searchEl = document.getElementById('searchUsersInput');
        const roleEl = document.getElementById('filterRole');
        const statusEl = document.getElementById('filterStatus');
        const creationMinEl = document.getElementById('filterAccountCreationDateMin');
        const creationMaxEl = document.getElementById('filterAccountCreationDateMax');

        // Verifica se os elementos de filtro existem antes de ler os valores
        if (!searchEl || !roleEl || !statusEl || !creationMinEl || !creationMaxEl) return;

        const search = searchEl.value.toLowerCase();
        const role = roleEl.value.toLowerCase();
        const status = statusEl.value.toLowerCase();
        const accountCreationMin = creationMinEl.value;
        const accountCreationMax = creationMaxEl.value;
                                                    
        const linhas = document.querySelectorAll('#corpoTabelaUsers tr');
                                                    
        linhas.forEach(function(linha) {
            const username = linha.cells[1]?.textContent.toLowerCase() || '';
            const roleCell = linha.cells[2]?.textContent.toLowerCase() || '';
            const statusCell = linha.cells[4]?.textContent.toLowerCase() || '';
            const accountCreation = linha.cells[5]?.textContent || '';
        
            const match =
                username.includes(search) &&
                (role === '' || roleCell.includes(role)) &&
                (status === '' || statusCell.includes(status)) &&
                (accountCreationMin === '' || accountCreation >= accountCreationMin) &&
                (accountCreationMax === '' || accountCreation <= accountCreationMax);
        
            linha.style.display = match ? '' : 'none';
        });
    }

    document.getElementById('btnLimparFiltrosUsers').addEventListener('click', function () {
        document.getElementById('searchUsersInput').value = '';
        document.getElementById('filterRole').value = '';
        document.getElementById('filterStatus').value = '';
        document.getElementById('filterAccountCreationDateMin').value = '';
        document.getElementById('filterAccountCreationDateMax').value = '';
    
        filtrarTabelaUsers();
    });

    ['searchUsersInput', 'filterRole', 'filterStatus',
     'filterAccountCreationDateMin', 'filterAccountCreationDateMax']
        .forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('input', filtrarTabelaUsers);
        });

    // ---- Script que adiciona/atualiza produtos ----
    document.getElementById('btnSalvarProduct').addEventListener('click', function () {
        const image = document.getElementById('inputImage').value;
        const color = document.getElementById('inputColor').value;
        const productName = document.getElementById('inputProductName').value;
        const category = document.getElementById('inputCategorySelect').value;
        const price = document.getElementById('inputPrice').value;
        const sales = document.getElementById('inputSales').value;
        const revenue = document.getElementById('inputRevenue').value;
        

        if (!productName || !category || !price || !sales || !revenue) {
            alert('Fill all fields!');
            return;
        }
    
        
        const product = new Produto(image, color, productName, category, '', price, '', '', sales, revenue);

        const novaLinha = `
            <tr>
                <td><img src="${product.image || 'productImage1.jpg'}" alt="product image" style="width: 40px; height: 40px; object-fit: cover;"></td>
                <td><div style="width: 40px; height: 40px; background-color: ${product.color};"></div></td>
                <td>${product.productName}</td>
                <td>${product.category}</td>
                <td>-</td>
                <td>${product.price}</td>
                <td>-</td>
                <td>-</td>
                <td>${product.sales}</td>
                <td>${product.revenue}</td>
                <td>
                    <button class="btn btn-sm btn-warning btnUpdateProduct">Update</button>
                    <button class="btn btn-sm btn-danger btnDeleteProduct">Delete</button>
                </td>
            </tr>`;
        
        const editIndex = this.dataset.editLinha;
        const tbody = document.getElementById('corpoTabelaProduct');
        
        if (editIndex !== undefined && editIndex !== '') {
            // UPDATE - substitui a linha existente
            tbody.rows[editIndex].outerHTML = novaLinha;
            delete this.dataset.editLinha;
        } else {
            // INSERT - adiciona nova linha
            tbody.innerHTML += novaLinha;
        }
          
        document.getElementById('inputImage').value = '';
        document.getElementById('inputProductName').value = '';
        document.getElementById('inputCategorySelect').value = '';
        document.getElementById('inputPrice').value = '';
        document.getElementById('inputSales').value = '';
        document.getElementById('inputRevenue').value = '';
    
        bootstrap.Modal.getOrCreateInstance(document.getElementById('insertProductModal')).hide();
    });

    // ---- Script de UPDATE e DELETE de Produtos ----
    document.getElementById('corpoTabelaProduct').addEventListener('click', function(e) {
        const linha = e.target.closest('tr');

        // DELETE
        if (e.target.classList.contains('btnDeleteProduct')) {
            linha.remove();
        }
    
        // UPDATE - abre o modal preenchido com os dados da linha
        if (e.target.classList.contains('btnUpdateProduct')) {
            const cells = linha.querySelectorAll('td');
            const colorDiv = cells[1].querySelector('div');
            const cor = colorDiv ? colorDiv.style.backgroundColor : 'rgb(0,0,0)';

            document.getElementById('inputImage').value = cells[0].querySelector('img').src;
            document.getElementById('inputColor').value = rgbToHex(cor);
            document.getElementById('inputProductName').value = cells[2].textContent.trim();
            document.getElementById('inputCategorySelect').value = cells[3].textContent.trim();
            document.getElementById('inputPrice').value = cells[5].textContent.replace('€','').trim();
            document.getElementById('inputSales').value = cells[8].textContent.trim();
            document.getElementById('inputRevenue').value = cells[9].textContent.replace('€','').trim();
        
            // Guarda referência à linha para atualizar depois
            document.getElementById('btnSalvarProduct').dataset.editLinha = Array.from(
                document.getElementById('corpoTabelaProduct').rows
            ).indexOf(linha);
        
            bootstrap.Modal.getOrCreateInstance(document.getElementById('insertProductModal')).show();
        }
    });

    // Converte RGB para HEX
    function rgbToHex(rgb) {
        const result = rgb.match(/\d+/g);
        if (!result || result.length < 3) return '#000000';
        return '#' + result.slice(0, 3).map(x => {
            const hex = parseInt(x).toString(16);
            return hex.length === 1 ? '0' + hex : hex;
        }).join('');
    }

    // *IMPORTANTE* Limpa o dataset de edição e os campos do modal quando ele for fechado
    document.getElementById('insertProductModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('btnSalvarProduct').dataset.editLinha = '';
        document.getElementById('inputImage').value = '';
        document.getElementById('inputColor').value = '#000000';
        document.getElementById('inputProductName').value = '';
        document.getElementById('inputCategorySelect').value = '';
        document.getElementById('inputPrice').value = '';
        document.getElementById('inputSales').value = '';
        document.getElementById('inputRevenue').value = '';
    });

    // ---- Script de filtragem de Produtos ----
    function filtrarTabela() {
        const search = document.getElementById('searchProduct').value.toLowerCase();
        const category = document.getElementById('filterCategory').value.toLowerCase();
        const priceMin = parseFloat(document.getElementById('filterPriceMin').value) || 0;
        const priceMax = parseFloat(document.getElementById('filterPriceMax').value) || Infinity;
        const salesMin = parseFloat(document.getElementById('filterSalesMin').value) || 0;
        const salesMax = parseFloat(document.getElementById('filterSalesMax').value) || Infinity;
        const revenueMin = parseFloat(document.getElementById('filterRevenueMin').value) || 0;
        const revenueMax = parseFloat(document.getElementById('filterRevenueMax').value) || Infinity;
    
        const linhas = document.querySelectorAll('#corpoTabelaProduct tr');
    
        linhas.forEach(function(linha) {
            const productName = linha.cells[2]?.textContent.toLowerCase() || '';
            const cat = linha.cells[3]?.textContent.toLowerCase() || '';
            const price = parseFloat(linha.cells[5]?.textContent.replace('€', '')) || 0;
            const sales = parseFloat(linha.cells[8]?.textContent) || 0;
            const revenue = parseFloat(linha.cells[9]?.textContent.replace('€', '')) || 0;
        
            const match =
                productName.includes(search) &&
                (category === '' || cat.includes(category)) &&
                price >= priceMin && price <= priceMax &&
                sales >= salesMin && sales <= salesMax &&
                revenue >= revenueMin && revenue <= revenueMax;
        
            linha.style.display = match ? '' : 'none';
        });
    }

    document.getElementById('btnLimparFiltros').addEventListener('click', function () {
        document.getElementById('searchProduct').value = '';
        document.getElementById('filterCategory').value = '';
        document.getElementById('filterPriceMin').value = '';
        document.getElementById('filterPriceMax').value = '';
        document.getElementById('filterSalesMin').value = '';
        document.getElementById('filterSalesMax').value = '';
        document.getElementById('filterRevenueMin').value = '';
        document.getElementById('filterRevenueMax').value = '';
    
        filtrarTabela();
    });

    ['searchProduct', 'filterCategory', 'filterPriceMin', 'filterPriceMax',
     'filterSalesMin', 'filterSalesMax', 'filterRevenueMin', 'filterRevenueMax']
        .forEach(id => document.getElementById(id).addEventListener('input', filtrarTabela));

    // Script que adiciona/atualiza posts
    document.getElementById('btnSalvarPost').addEventListener('click', function () {
        const image = document.getElementById('post-inputImage').value;
        const text = document.getElementById('post-inputText').value;
        const username = document.getElementById('post-inputUsername').value;
        const date = document.getElementById('post-inputDate').value;
        const likeCount = document.getElementById('post-inputLikeCount').value;
    
        if (!text || !username || !date || !likeCount) {
            alert('Fill all fields!');
            return;
        }

        const post = new Post(null, username, date, text, image, likeCount);

        const novaLinha = `
            <tr>
                <td><img src="${post.image_post || 'productImage1.jpg'}" alt="post image" style="width: 40px; height: 40px; object-fit: cover;"></td>
                <td>${post.text_post}</td>
                <td>${post.id_utilizador}</td>
                <td>${post.dt_postagem}</td>
                <td>${post.like_count}</td>
                <td>
                    <button class="btn btn-sm btn-warning btnUpdatePost">Update</button>
                    <button class="btn btn-sm btn-danger btnDeletePost">Delete</button>
                </td>
            </tr>`;

        const editIndex = this.dataset.editLinha;
        const tbody = document.getElementById('corpoTabelaPosts');
        
        if (editIndex !== undefined && editIndex !== '') {
            // UPDATE - substitui a linha existente
            tbody.rows[editIndex].outerHTML = novaLinha;
            delete this.dataset.editLinha;
        } else {
            // INSERT - adiciona nova linha
            tbody.innerHTML += novaLinha;
        }
          
        document.getElementById('post-inputImage').value = '';
        document.getElementById('post-inputText').value = '';
        document.getElementById('post-inputUsername').value = '';
        document.getElementById('post-inputDate').value = '';
        document.getElementById('post-inputLikeCount').value = '';
    
        bootstrap.Modal.getOrCreateInstance(document.getElementById('insertPostModal')).hide();
    });

    // ---- Script de UPDATE e DELETE de Posts ----
    document.getElementById('corpoTabelaPosts').addEventListener('click', function(e) {
        const linha = e.target.closest('tr');

        // DELETE
        if (e.target.classList.contains('btnDeletePost')) {
            linha.remove();
        }

        // UPDATE
        if (e.target.classList.contains('btnUpdatePost')) {
            const cells = linha.querySelectorAll('td');

            document.getElementById('post-inputImage').value = cells[0].querySelector('img').src;
            document.getElementById('post-inputText').value = cells[1].textContent;
            document.getElementById('post-inputUsername').value = cells[2].textContent;
            document.getElementById('post-inputDate').value = cells[3].textContent;
            document.getElementById('post-inputLikeCount').value = cells[4].textContent;

            document.getElementById('btnSalvarPost').dataset.editLinha = Array.from(
                document.getElementById('corpoTabelaPosts').rows
            ).indexOf(linha);

            bootstrap.Modal.getOrCreateInstance(document.getElementById('insertPostModal')).show();
        }
    });

    document.getElementById('insertPostModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('btnSalvarPost').dataset.editLinha = '';
        document.getElementById('post-inputImage').value = '';
        document.getElementById('post-inputText').value = '';
        document.getElementById('post-inputUsername').value = '';
        document.getElementById('post-inputDate').value = '';
        document.getElementById('post-inputLikeCount').value = '';
    });

    document.querySelectorAll('[id^="formEditUser-"]').forEach(form => {
    form.addEventListener('submit', async function(e) {
        e.preventDefault(); // impede o redirect normal

        const userId = this.id.replace('formEditUser-', '');
        const formData = new FormData(this); // pega nos dados do form

        const res = await fetch('/users/' + userId + '/update', {
            method: 'POST',
            body: formData
        });

        if (res.ok) {
            bootstrap.Modal.getInstance(
                document.querySelector('.modal.show')
            ).hide();
            // Atualiza a linha da tabela
            const usernameInput = form.querySelector('[name="username"]');
            const emailInput = form.querySelector('[name="email"]');
            const linha = document.querySelector(`[data-id="${userId}"]`)?.closest('tr');
        
            if (linha) {
                linha.querySelector('[data-campo="username"]').textContent = usernameInput.value;
                linha.querySelector('[data-campo="email"]').textContent = emailInput.value;
            }
        }
        });
    });
</script>

</body>
</html>