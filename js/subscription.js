const success 		= document.getElementById('form_success')
let		successDisp	= false
const formDisp		= document.getElementById('form_wrapper')
const form 				= document.getElementById('sub_tick_form')
const phone				= document.getElementById('user_phone')
// success.style.display = 'flex'
// success.style.backgroundColor = 'blue'

form.addEventListener('submit', (e)=>{
	e.preventDefault()
	success.style.display		= 'flex'
	formDisp.style.display	= 'none'
	successDisp							= true
	setTimeout(() => {
		if(successDisp)clearForm()
	}, 10000);
})

success.addEventListener('click', clearForm)

function clearForm(){
	success.style.display		= 'none'
	formDisp.style.display	= 'flex'
	successDisp						= false
	phone.focus()
	phone.value = ''
}