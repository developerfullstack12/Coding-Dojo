<?php
    /*
    template name:edit-Profile2
    
    */
    
    get_header(); 
?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
<div class="container rounded bg-white mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-heading">Purchase a Pass</h4>
    </div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="text-right1">Number of Months left:</h6>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="">
                <div class="row mt-2">
                    <div class="col-md-3 ">
                        <label class="labels1">Purchase new passes</label>
                    </div>
                    <div class="col-md-3 c_btn">
                        <button type="button" class="btn btn-warning c_monts_btn">1 Month<br/><span class="c_price">SGD$10</span></button>
                    </div>
                    <div class="col-md-3 c_btn">
                        <button type="button" class="btn btn-warning c_monts_btn">1 Month<br/><span class="c_price">SGD$10</span></button>
                    </div>
                    <div class="col-md-3 c_btn">
                        <button type="button" class="btn btn-warning c_monts_btn">1 Month<br/><span class="c_price">SGD$10</span></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 Purchase_btn">
            <div class="">
                <div class="row mt-2">
                    <div class="col-md-12 c_btn1">
                        <button type="button" class="c_purchse_btn">Purchase</button>
                    </div>
                </div>
            </div>
            <div class="">
        <h6 class="text-right2">You will be redirect to stripe to make payment</h6>
    </div>
    </div>
    
    <div class="col-md-12">
          <div class="">
              <h4 class="text-heading">Email Notification</h4><br/>
                <div class="row mt-2">
                    <div class="col-md-8">
                        <h6 class="text-right1">You will be redirect to stripe to make payment</h6>
                    </div>
                    <div class="col-md-4 c_btn">
                        <button type="button" class="btn_yes">Yes</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <button type="button" class="btn_no">No</button>
                    </div>
                </div>
            </div><br/><br/>
        <h4 class="text-heading">Payments Recoreds</h4>
<table>
  <tr class="tab_heading">
    <th>Date</th>
    <th>ID</th>
    <th>No. of Pass</th>
    <th>Amount</th>
  </tr>
  <tr>
    <td>12/01/22 13:44:32</td>
    <td>12451212</td>
    <td>10</td>
    <td>$10</td>
  </tr>
  <tr>
    <td>12/01/22 13:44:32</td>
    <td>12451212</td>
    <td>10</td>
    <td>$10</td>
  </tr>
  <tr>
    <td>12/01/22 13:44:32</td>
    <td>12451212</td>
    <td>10</td>
    <td>$10</td>
  </tr>
</table>
    
</div>

</div>
</div>
</div>
<style>

button.c_monts_btn, .c_purchse_btn:hover {
    background: #6e6e6e;
    color: #fff;
    border: 1px solid #6e6e6e;
}
button.btn_yes:hover {
    background: #6e6e6e;
    color: #fff;
    border: 1px solid #6e6e6e;
}

.c_btn1 {
    text-align: center;
}
.btn_yes {
    background: #ffe700;
    border: 1px solid #ffe700;
    padding: 0px 28px;    
    font-size: 28px;
    font-weight: bold;
    color: #000;
    border-radius: 15px;
}
.btn_no {
    background: #f4f4f4;
    border: 1px solid #f4f4f4;
    padding: 0px 28px;    
    font-size: 28px;
    font-weight: bold;
    color: #000;
    border-radius: 15px;
}

button.c_monts_btn:hover {
    background: #6e6e6e;
    color: #fff;
    border: 1px solid #6e6e6e;
}
.labels1 {
    font-size: 22px;
    margin: 28px 0px 0px;
    color: #000000;
    font-weight: 400;
}
h4.text-heading {
    font-size: 28px;
    color: #000000;
    font-weight: 600;
    margin-bottom: 16px;
}
h6.text-right1 {
    font-size: 22px;
}
.c_monts_btn {
    color: #000000;
    background: #f4f4f4;
    border: 1px solid #f4f4f4;
    padding: 12px 45px;
    border-radius: 15px;
}
.c_btn {
    text-align: right;
}
.c_monts_btn {
    font-size: 28px;
    font-weight: bold;
    line-height: 28px;
}
span.c_price {
    font-size: 22px;
    font-weight: 400 !important;
}
.c_purchse_btn {
    color: #000000;
    background: #ffe700;
    border: 1px solid #ffe700;
    padding: 10px 70px;
    border-radius: 15px;
    font-weight: bold;
    font-size: 28px;
}
.Purchase_btn {
    margin: 35px 0px;
}
h6.text-right2 {
    text-align: center;
    margin: 18px 0px;
    font-size: 22px;
}



table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

tr:nth-child(even) {
  background-color: #dcdcdc;
}



tr.tab_heading th {
    background: #6e6e6e;
    color: #fff;
    font-size: 20px;
    padding: 30px 30px 25px 90px;
}
tr td {
    font-size: 20px;
    padding: 30px 30px 25px 90px;
}
td:first-child, th:first-child {
    font-size: 20px;
    padding: 30px 30px 25px 90px;
}
</style>
<?php
    get_footer();
    
    ?>