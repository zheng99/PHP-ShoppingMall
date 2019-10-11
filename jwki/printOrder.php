<?
	echo"printOrder.php<br>";
	include "showCart.php";
?>
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script>
//daum객체는 위에서 설정한 라이브러리 안쪽에 들어있다.
function daumZipCode(who) {
 new daum.Postcode({
     oncomplete: function(data) {
         var fullAddr = ''; // 최종 주소 변수
         var extraAddr = ''; // 조합형 주소 변수

         if (data.userSelectedType === 'R') {  //R은 도로명 주소이다.
             fullAddr = data.roadAddress;

         } else { // 사용자가 지번 주소를 선택했을 경우(J)
             fullAddr = data.jibunAddress; //도로명 주소가 아니라면.. 지번주소.
         }

         // 사용자가 선택한 주소가 도로명 타입일때 조합한다.
         if(data.userSelectedType === 'R'){
             //법정동명이 있을 경우 추가한다.
             if(data.bname !== ''){
                 extraAddr += data.bname;
             } //도로명 주소일때는 법에 맞춰서 '동' 이름을 추가해야 한다.


             // 건물명이 있을 경우 추가한다.
             if(data.buildingName !== ''){
                 extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
             }

             // 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
             fullAddr += (extraAddr !== '' ?
                     ' ('+ extraAddr +')' : '');
         }

         // 우편번호와 주소 정보를 해당 필드에 넣는다.
         //5자리 새우편번호 사용

		 if(who == 1)
		 {
			document.getElementById('o_zip').value = data.zonecode;
			document.getElementById('o_addr1').value = fullAddr; //addr1에 확정된 주소값의 풀네임이 들어간다.

			// 커서를 상세주소 필드로 이동한다.
			// 커서를 이동시켜서 깜빡이게끔 한다.
			document.getElementById('o_addr2').focus();
		 } else
		 {
			document.getElementById('r_zip').value = data.zonecode;
			document.getElementById('r_addr1').value = fullAddr; //addr1에 확정된 주소값의 풀네임이 들어간다.

			// 커서를 상세주소 필드로 이동한다.
			// 커서를 이동시켜서 깜빡이게끔 한다.
			document.getElementById('r_addr2').focus();
		 }

     }
 }).open();
}
</script>
<?

	$csql = "select * from $cart_table where ip='$_SESSION[$sess_id]' and time='$_SESSION[$sess_time]' order by idx asc";
	$cresult = mysql_query($csql);
	$cdata = mysql_fetch_array($cresult);


	//다음 지도 검색을 위해 id값 필요
	//checkSame() 주문자와 수령인이 같을시 버튼 클릭하면 자동 입력
	echo"
	<script>
		function checkSame()
		{
			var f = document.orderForm;

			//alert(f.same.checked);
			if(f.same.checked == true)
			{
				f.r_name.value = f.o_name.value;
				f.r_mobile.value = f.o_mobile.value;
				f.r_zip.value = f.o_zip.value;
				f.r_addr1.value = f.o_addr1.value;
				f.r_addr2.value = f.o_addr2.value;
			}
		}

		function checkError()
		{
			var f = document.orderForm;
			if(f.o_zip.value ==''){
				alert('Zip code search');
				return false;
			}
			if(f.o_addr1.value ==''){
				alert('Address search');
				return false;
			}
			if(f.r_zip.value ==''){
				alert('Zip code search');
				return false;
			}
			if(f.r_addr1.value ==''){
				alert('Address search');
				return false;
			}
		}
		</script>";

		$usql = "select * from $user_table where id='$_SESSION[$sess_id]'";
		$uresult = mysql_query($usql);
		$uinfo = mysql_fetch_array($uresult);

		echo"
		<form name=orderForm method=post action='$PHP_SELF?cmd=order' onSubmit=\"return checkError()\">
		<table border=0 width=90%>
			<tr>
				<td colspan=4 class=menuRight>2019-09-24 Info</td>
			</tr>
			<tr>
				<td class=menuLeft>Name</td>
				<td class=menuRight><input type=text name=o_name class=inputBox required value='$uinfo[name]'></td>
				<td class=menuLeft>ID</td>
				<td class=menuRight>$_SESSION[$sess_id]</td>
			</tr>

			<tr>
				<td class=menuLeft>전화번호</td>
				<td class=menuRight><input type=text name=o_mobile class=inputBox required value='$uinfo[mobile]'></td>
				<td class=menuLeft>우편번호</td>
				<td class=menuRight><input type=text name=o_zip id=o_zip class=inputBox readonly required placeholder='Search Click' onClick=daumZipCode(1) value='$uinfo[zip]'></td>
			</tr>

			<tr>
				<td class=menuLeft>Address</td>
				<td class=menuRight colspan=3><input type=text name=o_addr1 id=o_addr1 class=inputBox readonly value='$uinfo[addr1]'></td>
			</tr>

			<tr>
				<td class=menuLeft>Address2</td>
				<td class=menuRight colspan=3><input type=text name=o_addr2 id=o_addr2 class=inputBox required value='$uinfo[addr2]'></td>
			</tr>


			<tr>
				<td colspan=4 class=menuRight>Recipient Info(<input type=checkbox name=same onClick=checkSame()> 주문자와 동일)</td>
			</tr>
			<tr>
				<td class=menuLeft>Name</td>
				<td class=menuRight><input type=text name=r_name class=inputBox required></td>
				<td class=menuLeft>ID</td>
				<td class=menuRight>$_SESSION[$sess_id]</td>
			</tr>

			<tr>
				<td class=menuLeft>Phone</td>
				<td class=menuRight><input type=text name=r_mobile class=inputBox required></td>
				<td class=menuLeft>Zip Code</td>
				<td class=menuRight><input type=text name=r_zip id=r_zip class=inputBox readonly required placeholder='Search Click'  onClick=daumZipCode(2)></td>
			</tr>

			<tr>
				<td class=menuLeft>Address</td>
				<td class=menuRight colspan=3><input type=text name=r_addr1 id=r_addr1 class=inputBox readonly></td>
			</tr>

			<tr>
				<td class=menuLeft>Address2</td>
				<td class=menuRight colspan=3><input type=text name=r_addr2 id=r_addr2 class=inputBox required></td>
			</tr>

			<tr>
				<td class=menuLeft>Request</td>
				<td class=menuRight colspan=3>
				<textarea name=memo class=inputBox style='height:150px; line-height:200%;'></textarea></td>
			</tr>

			<tr>
				<td class=menuLeft colsapn=4 >
					<input type=submit value='Order' class=submit>
				</td>
			</tr>

		</table>
	</form><br><br>
	";

?>