<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="./select.js" defer></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Your name here">
    <link rel="stylesheet" href="ACMEpurchase.css">
    <title>ACME Customer Care Portal</title>
</head>

<body>
    <header>
        <h1>ACME Corporation</h1>
        <div class="tagline">yes we deliver!</div>
    </header>


    <main>
        <h2>Customer Invoice</h2>
        <table class="invoice">
            <tr>
                <th class="invoiceheader">Item</th>
                <th class="invoiceheader">Retail Cost</th>
                <th class="invoiceheader">Discount</th>
                <th class="invoiceheader">Total</th>
            </tr>

            <?php 
            include ("Instrument.php");
    session_start();
   
    $cart = array();
    if(isset($_SESSION['cart'])){
        foreach( $_SESSION['cart'] as $ins){
        $cart[] = $ins;
    }}
    if($_POST){ 
        
        $instrument = new Instrument($_POST['item'],$_POST['retail'],$_POST['total'],$_POST['discount']);

        $cart[] = serialize($instrument);
        $_SESSION['cart'] = $cart;
      }
      
    if(isset($_SESSION['cart'])){
        foreach( $_SESSION['cart'] as $ins){
            $unserializedIns = unserialize($ins);
    ?>
            <!-- HERE IS WHERE INVOICE ITEMS ARE LISTED -->

            <?php  echo "<tr><td>".$unserializedIns->name."</td><td class=\"centered\">"; ?>

            <?php  echo $unserializedIns->retail."</td><td class=\"centered\">";?>

            <?php echo $unserializedIns->discount."</td><td class=\"centered\">"; ?>

            <?php   echo $unserializedIns->finalPrice."</td></tr>";?>

            <?php 
        }
    }
?>
            <!-- THE FOLLOWING LINE IS ALWAYS PRESENT -->

            <tr class="totalline">
                <td colspan="3">Invoice total</td>
                <td class="centered">
                    <?php 
                    $totalPrice = 0;
                    if(isset($_SESSION['cart'])){
                        foreach( $_SESSION['cart'] as $ins){
                            $unserializedIns = unserialize($ins);
                            $totalPrice += $unserializedIns->finalPrice;
                        }
                    }
                    echo $totalPrice;
                    ?>
                </td>
            </tr>
        </table>


        <div id="purchase">
            <a href="thanks.php"><button id="submitorder" <?php if(!isset($_SESSION['cart'])){echo "disabled" ;}
                    ?>>Purchase</button></a>
        </div>

        <hr>
        <script>
            function changeHandler(selectOption) {
                var itemEle = document.getElementById("item")
                var retailEle = document.getElementById('retail')
                var finalPriceEle = document.getElementById("total")
                var discountEle = document.getElementById('discount')
                discountEle.value = ""
                //步骤一:创建异步对象
                var ajax = new XMLHttpRequest();

                //步骤二:设置请求的url参数,参数一是请求的类型,参数二是请求的url,可以带参数,动态的传递参数starName到服务端

                ajax.open('get', 'test.php?itemSelected=' + selectOption.value);
                ajax.setRequestHeader("Content-type", "application/json");
                //步骤三:发送请求
                ajax.send();
                //步骤四:注册事件 onreadystatechange 状态改变就会调用
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4 && ajax.status == 200) {

                        var item = ajax.responseText
                        console.log(ajax.responseText)
                        item = JSON.parse(item)
                        console.log(item)

                        itemEle.value = item[0]
                        finalPriceEle.value = item[1]
                        retailEle.value = item[1]
                    }
                }
            }



        </script>

        <form action="ACMEpurchases.php" method="POST" onsubmit="return validateForm()">
            <fieldset class="additem">
                <legend>Add Item to Order</legend>
                <select id="newitem" name="instruments" onchange="changeHandler(this)">
                    <option value=""> add item </option>
                    <!-- make a list of options using PHP and the 
                    provided array of musical instruments. -->
                    <?php
                        include "ACMEarray.php";
                        foreach($inventory as $adi){
                        echo "<option value='$adi[0]'>$adi[0]</option>";}
                        echo "</select>"
                    ?>

                    <div class="itemdetails">

                        <label for="item">Item:</label>

                        <input type="text" name="item" id="item" readonly />
                    </div>
                    <div class="itemdetails">
                        <label for="retail">Price:</label>
                        <input type="text" name="retail" id="retail" readonly>
                        <label for="discount">Discount:</label>
                        <input type="text" name="discount" id="discount">
                        <label for="total">Total:</label>
                        <input type="text" name="total" id="total" readonly>
                    </div>

                    <div class="purchase">
                        <button type="submit">Add to Invoice</button>
                    </div>

                    <p class="centered">
                        Attention, all discounts will be verified by our software.
                    </p>

            </fieldset>
        </form>

        <footer>ACME Coporation for all that you can scheme up!</footer>
    </main>
    <script>
        var discountEle = document.getElementById("discount")
        var itemEle = document.getElementById("item")
        discountEle.addEventListener('input', (e) => {
            var item = document.getElementById('item')
            if (!item) {
                alert("Select item first")
                return false
            }
            //步骤一:创建异步对象
            var ajax = new XMLHttpRequest();

            //步骤二:设置请求的url参数,参数一是请求的类型,参数二是请求的url,可以带参数,动态的传递参数starName到服务端
            var discount = e.target.value
            if (discount.endsWith('%')) {
                discount = discount.slice(0, -1)
            }
            if (!discount) {
                return false
            }
            ajax.open('get', 'test.php?discount=' + discount + '&item=' + item.value);
            //步骤三:发送请求
            ajax.send();
            //步骤四:注册事件 onreadystatechange 状态改变就会调用
            ajax.onreadystatechange = function () {
                if (ajax.readyState == 4 && ajax.status == 200) {
                    //步骤五 如果能够进到这个判断 说明 数据 完美的回来了,并且请求的页面是存在的
                    if (ajax.responseText == -1) {
                        alert("Discount is too high, please lower the discount!")
                    } else {
                        document.getElementById('total').value = ajax.responseText
                    }

                }
            }

            if (!e.target.value.endsWith('%') && e.target.value) { e.target.value = e.target.value + "%" }

        })

        function validateForm(){
            console.log(discountEle.value)
            if(!itemEle.value){
                alert("Item could not be null")
            }
            
            return  !!itemEle.value
        }

    </script>
</body>

</html>