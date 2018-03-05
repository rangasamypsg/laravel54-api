<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
img.scroll{
    width: 65%;
    margin-left: 63px;
}
a {
    color: #2d32e6;
	
}

.header{
    padding: 24px 35px;
   
    background-color: #4c4c4c;
    text-align: left;
    border-bottom: 1px solid #e0e0e0;
}
.align{
    border: 2px solid #4c4c4c;
    width: 36%;
    margin-left: 29%;
    margin-top: 2%;
}
h2.main{
	
    text-align: center;
    padding-top: 15px;
	
}
.footer {
    padding: 10px 20px;
   
    background-color: #ffffff;
    text-align: left;
    border-top: 1px solid #e0e0e0;
    padding-top: 0px;
    padding-bottom: 0px;
}

.mark{padding-left: 19px;margin-top: -10px;}
.mark1{
    padding-left: 19px;
    padding-right: 15px;
    margin-bottom: -13px;
}
h5.head{font-size: 17px;}
p.head1{margin-right: 3px;}
p.contant{
	text-align: center;
    margin-right: 1px;
	padding-bottom: 15px;
}
.page{padding-bottom: 19px;}
h1.footer{color:#ffffff;}
@media only screen and (min-width: 1000px) and (max-width: 2000) {
    header {
        width:50%;
    }
}
.verify{
    color: #926b6b;
    padding: 6px 62px;
    font-size: 32px;
}

</style>
</head>
<body>
    <div class="align">
	   <div class="header">
		<a herf="#"><img src="{{ asset('assets/images/zoin-logo.png') }}"  class="scroll" align="middle" /></a>
       </div>
       <div class="page">
         <div class="mark">
		     <h2 class="head">Hi Vendor,</h2>
			 <p> New Loyalty Created For Zoin Account</p>			 
			 
			 <p>Details are given below :</p>
			 <div style="margin-left: 50px;">
    			<p><strong> Loyalty Id:</strong> {{ $loyalty_id }} </p>
    			<p><strong> Max Checkin:</strong> {{ $max_checkin }} </p>
                <p><strong> Max Bill Amount:</strong> {{ $max_bill_amount }} </p>
                <p><strong> Zoin Point:</strong><a href="#"> {{ $zoin_point }} </a></p>
                <p><strong> Description:</strong> {{ $description }} </p>
                <p><strong> Vendor Id:</strong> {{ $vendor_id }} </p>
		</div>	   
	    <div class="footer">
	         <p>No:33, Katoor Road, P.N.Palayam, Coimbatore - 641037, India.</p>
			 <p>&#169; episode technologies private limited.</p>
	   </div>
	</div>      
</body>
</html>