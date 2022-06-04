<?php
include 'Machine.php';

$arr_nominal = [2000, 5000, 10000, 20000, 50000];
$data = new Machine();

$data_barang = json_decode($data->getDataBarang());
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Vending Machine</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="jumbotron text-center">
    <h1>Vending Machine</h1>
    <p>Come on, get what you want !</p> 
</div>
  
<div class="container">
    <div class="row">
        <?php foreach ($data_barang as $k => $v) { ?>
            <div class="col-sm-4" style="padding: 2px 2px 2px 2px;">
                <div class="card">
                    <img class="card-img-top" src="<?php echo $v[2]; ?>" alt="Card image" style="width: auto; height: 18rem;">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo $k; ?></h4>
                        <p class="card-text" id="tprice<?php echo $v[3]; ?>">Rp. <?php echo $v[0]; ?></p>
                        <input type="hidden" id="price<?php echo $v[3]; ?>" value="<?php echo $v[0]; ?>">
                        <a id="processBtn" name="processBtn" onclick="parsingId(<?php echo $v[3]; ?>)" class="btn btn-primary" style="float: right;">Buy</a>
                    </div>
                    <div class="card-footer text-muted">
                        <p id="tstock<?php echo $v[3]; ?>">Stock : <?php echo $v[1]; ?></p>
                        <input type="hidden" id="stock<?php echo $v[3]; ?>" value="<?php echo $v[1]; ?>">
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <footer class="page-footer text-center">
        <div class="footer-copyright py-3">
            © <?php echo date('Y'); ?> Copyright : vendMachine
        </div>
    </footer>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="buyModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
        <form action="#" method="POST">
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">You're money (Rp.)</label>
                <div class="col-sm-5">
                    <select id="buyerMoney">
                        <option value="0">0</option>
                        <?php foreach ($arr_nominal as $k => $v) { ?>
                            <option value="<?php echo $v; ?>"><?php echo $v; ?></option>
                        <?php } ?>
                    </select>
                    <small id="buyerMoneyHelp" class="form-text text-muted">Choose you're money nominal</small>
                    <input type="hidden" id="parsId">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">How Much</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="buyMuch" maxlength="3">
                    <small id="buyerMoneyHelp" class="form-text text-muted">Fill with how much you wanna buy</small>
                </div>
            </div>
        </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" onclick="buyProcess();">Checkout</button>
            <button type="button" class="btn btn-warning" onclick="buyClear();">Clear</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="backPayModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <p id="backPayText"></p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-success" data-dismiss="modal">Okey</button>
        </div>
        </div>
    </div>
</div>

</body>
</html>

<script>
    $(document).ready(function(){
        $('[name=processBtn]').on('click', function(){
            $('#buyModal').modal('toggle');
            $('#buyModal').modal('show');
        });
    });

    function parsingId(id) {
        $('#parsId').val("");
        $('#parsId').val(id);
    }

    function buyClear() {
        $('#parsId').val("");
        $('#buyerMoney').val(0);
        $('#buyMuch').val("");
    }

    function buyProcess() {
        var id = $('#parsId').val();
        const arrNominal = [2000, 5000, 10000, 20000, 50000];
        var buyerMoney = parseInt($('#buyerMoney').val());
        var buyMuch = parseInt($('#buyMuch').val());
        var price = parseInt($('#price' + id).val());
        var stock = parseInt($('#stock' + id).val());
        var message = "";
        var backPay = 0;
        var theStock = 0;
        
        if (stock < buyMuch) {
            message = "you buy to much, stock is not enough !";
            afterProcess(message, 0, stock, 0, id);
            return false;
        } else if (stock == 0) {
            message = "sorry stock is empty, please choose another one";
            afterProcess(message, 0, stock, 0, id);
            return false;
        }

        if (buyerMoney < price) {
            message = "you're money is not enough !";
            afterProcess(message, 0, stock, 0, id);
            return false;
        }

        backPay = buyerMoney - price;
        theStock = stock - buyMuch;
        message = "Transaction successfull, here you're changes : Rp. " + backPay;
        afterProcess(message, 1, theStock, backPay, id);
    }

    function afterProcess(message, status, stock, backPay, id) {
        /*
        message : text for buyer
        status : 0 or 1 (failed or success)
        stock : stock after buyed
        */

        $('#tstock' + id).text("");
        $('#stock' + id).val(0);
        $('#tstock' + id).text("Stock : " + stock);
        $('#stock' + id).val(stock);
        $('#backPayText').text("");
        $('#backPayText').text(message);

        $('#buyModal').modal('toggle');
        $('#buyModal').modal('hide');
        buyClear();
    
        $('#backPayModal').modal('toggle');
        $('#backPayModal').modal('show');
    }
</script>
