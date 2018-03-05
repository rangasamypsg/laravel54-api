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
       <!--<img class="scroll" src="logo.png" align="middle"> -->
       <a herf="#"><img src="{{ asset('assets/images/zoin-logo.png') }}"  class="scroll" align="middle" /></a>
       </div>
       <div class="page">
         <div class="mark">
		     <h2 class="head">Hi {{ $contact_person }}</h2>
			 <p class="head1">Thanks for becoming a zoin member. Kindly view our terms and conditions given below.</p>
			 <p>Click here to view the <a href="#">Terms & Conditions.</a></p>
			
			 <p>Zoin Team</p>
		 </div>
		     <hr width="100%" style="width: 378px;">
	     <div class="mark1">
	         <p>zoin is the platform where users and merchants in a locality discover, interact and transact. For merchants, we are the go-to platform for spotlighting their uniqueness while boosting their revenenues and utilization through real-time promotions, loyalty programmes and content updates. For users, we are the destination to find out the buzz in their locality and discover interesting people to connect with and events / merchants to go to.</p>
		 </div>
	  </div>
	   <div class="footer">
	         <p>No:33, Katoor Road, P.N.Palayam, Coimbatore - 641037, India.</p>
			 <p>&#169; episode technologies private limited.</p>
	   </div>
	</div>   
</body>
</html>