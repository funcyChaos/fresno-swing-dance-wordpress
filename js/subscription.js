const success 		= document.getElementById('form_success')
const succMsg			= document.getElementById('success_message')
const fail				= document.getElementById('form_fail')
const errMsg			= document.getElementById('error_message')
const results			= document.querySelectorAll('.main-hide')
let		result			= false
const form 				= document.getElementById('sub_tick_form')
const phone				= document.getElementById('user_phone')

form.addEventListener('submit', (e)=>{
	e.preventDefault()
	const number = phone.value.replace(/\D/g,'')
	fetch(`${wpVars.homeURL}/wp-json/subscription/v1/by-number/${number}`,{
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			'X-WP-Nonce':		wpVars.nonce,
		},
	})
	.then(res=>res.json())
	.then(obj=>{
		console.log(obj)
		if(obj.has_vouchers){
			succMsg.innerHTML				= `You have ${obj.vouchers} vouchers left`
			success.style.display		= 'flex'
			form.style.display			= 'none'
			result									= true
			setTimeout(()=>{
				if(result)clearForm()
			}, 10000)
		}else{
			errMsg.innerHTML = 'Out of vouchers'
			fail.style.display			= 'flex'
			form.style.display			= 'none'
			result									= true
			setTimeout(()=>{
				if(result)clearForm()
			}, 10000)
		}
	})
})

results.forEach(result=>result.addEventListener('click', clearForm))

function clearForm(){
	success.style.display		= 'none'
	fail.style.display			= 'none'
	form.style.display	= 'flex'
	result									= false
	phone.focus()
	phone.value = ''
}

phone.addEventListener('input', (e)=>{
	const x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/)
	e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '')
	const formatPattern = /^(\+0?1\s)?\(?\d{3}\)?[\s.-]\d{3}[\s.-]\d{4}$/
	const isValid = formatPattern.test(e.target.value)
	if (isValid) e.target.setCustomValidity('')
	else e.target.setCustomValidity('Must use a valid US phone number');
	if(!e.target.value)e.target.setCustomValidity('')
})