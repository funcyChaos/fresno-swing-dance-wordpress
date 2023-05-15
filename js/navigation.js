let 	showDropdown = false;
const dropdown		 = document.getElementById('nav_dropdown');

document.getElementById('menu_button').addEventListener('click', ()=>{
	if(!showDropdown){
		dropdown.style.display = 'block';
		showDropdown 					 = true;
	}else{
		dropdown.style.display = 'none';
		showDropdown 					 = false;
	}
});

window.addEventListener('resize', ()=>{
	dropdown.style.display	= 'none'
	showDropdown 						= false;
});

document.getElementById('content_click').addEventListener('click', ()=>{
	dropdown.style.display	= 'none'
	showDropdown 						= false;
});

function sendEmail() {
	Email.send({
		Host: "smtp.gmail.com",
		Username: "sender@email_address.com",
		Password: "Enter your password",
		To: 'receiver@email_address.com',
		From: "sender@email_address.com",
		Subject: "Sending Email using javascript",
		Body: "Well that was easy!!",
	})
		.then(function (message) {
			alert("mail sent successfully")
		});
}