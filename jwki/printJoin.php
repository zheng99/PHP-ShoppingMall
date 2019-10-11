<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script>
//daum객체는 위에서 설정한 라이브러리 안쪽에 들어있다.
function daumZipCode() {
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
         document.getElementById('zip').value = data.zonecode; 
         document.getElementById('addr1').value = fullAddr; //addr1에 확정된 주소값의 풀네임이 들어간다.
 
         // 커서를 상세주소 필드로 이동한다. 
         // 커서를 이동시켜서 깜빡이게끔 한다.
         document.getElementById('addr2').focus();
     }
 }).open();
}
</script>

<?
	// printJoin.php

	echo "
	<script>
		function checkError()
		{
			var f = document.joinForm;

			if(f.id.value =='')
			{
				alert('Check ID');
				return false;
			}
			if(f.name.value =='')
			{
				alert('Insert Name');
				f.name.focus();
				return false;
			}
			if(f.mobile.value =='')
			{
				alert('Insert Phone');
				f.mobile.focus();
				return false;
			}

			if(f.pass.value != f.pass2.value)
			{
				alert('The passwords are different');
				f.pass.focus();
				return false;
			}else
			{
				if(f.pass.value =='')
				{
					alert('Insert Password');
					f.pass.focus();
					return false;
				}
			}

			if(f.zip.value =='')
			{
				alert('Zip code search');
				return false;
			}
			if(f.addr1.value =='')
			{
				alert('Address search');
				return false;
			}
			if(f.addr2.value =='')
			{
				alert('Address2 search');
				return false;
			}


			if(f.agree.checked == false)
			{
				alert('Agree Check Please');
				f.agree.focus();
				return false;
			}
		}
	</script>

	<form name=joinForm method=post action='$PHP_SELF?cmd=join' onSubmit=\"return checkError()\">
	<table border=0 width=90%>
		<tr>
			<td colspan=4 class=menuLeft>Sign</td>
		</tr>
		<tr>
			<td class=menuLeft>ID</td>
			<td class=menuRight><input type=text name=id class=inputBox readonly placeholder='Search Click'></td>
			<td class=menuLeft><input type=button value='Search' onClick=\"window.open('checkid.php', 'MYORDER', 'resizable=yes scrollbars=yes width=400 height=300')\"></td>
			<td class=menuRight></td>
		</tr>
		<tr>
			<td class=menuLeft>Name</td>
			<td class=menuRight><input type=text name=name class=inputBox></td>
			<td class=menuLeft>Phone</td>
			<td class=menuRight><input type=text name=mobile class=inputBox></td>		
		</tr>
		<tr>
			<td class=menuLeft>Password</td>
			<td class=menuRight><input type=password name=pass class=inputBox></td>
			<td class=menuLeft>Password2</td>
			<td class=menuRight><input type=password name=pass2 class=inputBox></td>		
		</tr>
		<tr>
			<td class=menuLeft>Zip Code</td>
			<td class=menuRight><input type=text name=zip id=zip class=inputBox></td>
			<td class=menuLeft><input type=button value='Search' onClick=daumZipCode()></td>
			<td class=menuRight></td>
		</tr>		

		<tr>
			<td class=menuLeft>Address</td>
			<td class=menuRight colspan=3><input type=text name=addr1 id=addr1 class=inputBox></td>
		</tr>		
		<tr>
			<td class=menuLeft>Address2</td>
			<td class=menuRight colspan=3><input type=text name=addr2 id=addr2 class=inputBox></td>
		</tr>

		<tr>
			<td class=menuLeft>Agree</td>
			<td class=menuRight colspan=3><textarea name=policy class=inputBox style='height:100px;'>
			
			</textarea>
			<br><input type=checkbox name=agree>Agree
			
			</td>
		</tr>		

		<tr>
			<td colspan=4 class=menuLeft>
				<input type=submit class=submit value='Sign'>
			</td>
		</tr>
	</table>
	</form><br>
	";
?>