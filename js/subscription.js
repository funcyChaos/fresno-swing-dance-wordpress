const messageWrap	= document.getElementById('main_message')
const message			= document.getElementById('message_element')
let		showMes			= false
const tickForm		= document.getElementById('sub_tick_form')
const phone				= document.getElementById('user_phone')
const newUserForm	= document.getElementById('new_user_form')
const newPhone		= document.getElementById('new_phone')
const newInputs		= document.querySelectorAll('.new-user-inputs')
const forms				= [tickForm, newUserForm]
const newUserBtn	= document.getElementById('new_user')
const nuClose			= document.getElementById('nuf_close')

tickForm.addEventListener('submit', e=>{
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
		if(obj.error){
			message.innerHTML 				= obj.error
			toggleMessage()
			// setTimeout(()=>{
			// 	if(result)clearForm()
			// }, 10000)
		}else{
			message.innerHTML					= `${obj.first_name} has ${obj.vouchers} vouchers left`
			toggleMessage()
			// setTimeout(()=>{
			// 	if(result)clearForm()
			// }, 10000)
		}
	})
})

newUserBtn.addEventListener('click', ()=>{
	tickForm.style.display		= 'none'
	newUserForm.style.display	= 'flex'
})
nuClose.addEventListener('click', ()=>{
	newUserForm.style.display	= 'none'
	tickForm.style.display		= 'flex'
})
newUserForm.addEventListener('submit', e=>{
	e.preventDefault()
	data = new FormData(newUserForm)
	const firstName = data.getAll('first_name')[0]
	const lastName 	= data.getAll('last_name')[0]
	const number		= data.getAll('new_phone')[0].replace(/\D/g,'')
	fetch(`${wpVars.homeURL}/wp-json/subscription/v1/new-user`,{
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			'X-WP-Nonce':		wpVars.nonce,
		},
		body: JSON.stringify({
			first_name:		firstName,
			last_name:		lastName,
			phone:	number
		})
	})
	.then(res=>res.json())
	.then(obj=>{
		console.log(obj)
		if(obj.subscriber){
			message.innerHTML = 'Subscriber already exists'
			const div		= document.createElement('div')
			const fName = document.createElement('p')
			const lName = document.createElement('p')
			const pNum	= document.createElement('p')
			div.setAttribute('id','info_div')
			fName.innerHTML	= `First: ${obj.subscriber[0][0]}`
			lName.innerHTML	= `Last: ${obj.subscriber[0][1]}`
			pNum.innerHTML	= `Phone: ${obj.subscriber[0][2]}`
			div.appendChild(fName)
			div.appendChild(lName)
			div.appendChild(pNum)
			messageWrap.appendChild(div)
			toggleMessage()
		}else{
			message.innerHTML = 'Subscriber added'
			const fName = document.createElement('p')
			const lName = document.createElement('p')
			const pNum	= document.createElement('p')
			fName.innerHTML	= `First: ${obj.subscriber[0][0]}`
			lName.innerHTML	= `Last: ${obj.subscriber[0][1]}`
			pNum.innerHTML	= `Phone: ${obj.subscriber[0][2]}`
			messageWrap.appendChild(fName)
			messageWrap.appendChild(lName)
			messageWrap.appendChild(pNum)
			toggleMessage()
		}
	})
})

messageWrap.addEventListener('click', toggleMessage)

function toggleMessage(){
	if(!showMes){
		showMes										= true
		messageWrap.style.display	= 'flex'
		forms.forEach(form=>{
			form.style.display			= 'none'
		});
	}else{
		showMes										= false
		messageWrap.style.display	= 'none'
		message.innerHTML					= ''
		tickForm.style.display		= 'flex'
		phone.value 							= ''
		newInputs.forEach(input=>{
			input.value							= ''
		});
		const div = document.getElementById('info_div')
		if(div) div.remove()
		phone.focus()
	}
}

[phone, newPhone].forEach(item=>{
	item.addEventListener('input', e=>{
		const x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/)
		e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '')
		const formatPattern = /^(\+0?1\s)?\(?\d{3}\)?[\s.-]\d{3}[\s.-]\d{4}$/
		const isValid = formatPattern.test(e.target.value)
		if (isValid) e.target.setCustomValidity('')
		else e.target.setCustomValidity('Must use a valid US phone number');
		if(!e.target.value)e.target.setCustomValidity('')
	})
})

document.addEventListener('DOMContentLoaded',()=>{
	// clearForm()
})