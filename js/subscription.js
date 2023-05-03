const mainMessage		= document.getElementById('main_message')
const message				= document.getElementById('message_element')
let		showMessage		= false
const subTickForm		= document.getElementById('sub_tick_form')
const tickUserPhone	= document.getElementById('tick_user_phone')
const newUserForm		= document.getElementById('new_user_form')
const newUserPhone	= document.getElementById('new_phone')
const newInputs			= document.querySelectorAll('.new-user-inputs')
const forms					= [subTickForm, newUserForm]
const newUserBtn		= document.getElementById('new_user_btn')
const nuCloseBtn		= document.getElementById('nuf_close_btn')

subTickForm.addEventListener('submit', e=>{
	e.preventDefault()
	const number = tickUserPhone.value.replace(/\D/g,'')
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
			setTimeout(()=>{
				if(showMessage)toggleMessage()
			}, 10000)
		}else{
			message.innerHTML					= `${obj.first_name} has ${obj.vouchers} vouchers left`
			toggleMessage()
			setTimeout(()=>{
				if(showMessage)toggleMessage()
			}, 10000)
		}
	})
})

newUserBtn.addEventListener('click', ()=>{
	if(showMessage) toggleMessage()
	subTickForm.style.display		= 'none'
	newUserForm.style.display	= 'flex'
})
nuCloseBtn.addEventListener('click', ()=>{
	newUserForm.style.display	= 'none'
	subTickForm.style.display		= 'flex'
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
		if(obj.error){
			if(obj.subscriber){
				messageInfoDiv(
						'Subscriber already exists',
						obj.subscriber[0][0],
						obj.subscriber[0][1],
						obj.subscriber[0][2],
						obj.subscriber[0][3]
					)
			}
		}else if(obj.success){
			messageInfoDiv(
				'Subscriber Added',
				firstName,
				lastName,
				number,
				3
			)
		}
	})
})

function messageInfoDiv(mes, first, last, phone, vouchers){
	message.innerHTML 	= mes
	const div						= document.createElement('div')
	const fName 				= document.createElement('p')
	const lName 				= document.createElement('p')
	const pNum					= document.createElement('p')
	const vouchEl				= document.createElement('p')
	div.id 							= 'info_div'
	fName.innerHTML			= `First: ${first}`
	lName.innerHTML			= `Last: ${last}`
	pNum.innerHTML			= `Phone: ${phone}`
	vouchEl.innerHTML 	= `Vouchers: ${vouchers}`
	div.appendChild(fName)
	div.appendChild(lName)
	div.appendChild(pNum)
	div.appendChild(vouchEl)
	mainMessage.appendChild(div)
	toggleMessage()
}

mainMessage.addEventListener('click', toggleMessage)

function toggleMessage(){
	if(!showMessage){
		showMessage										= true
		mainMessage.style.display	= 'flex'
		forms.forEach(form=>{
			form.style.display			= 'none'
		});
	}else{
		showMessage										= false
		mainMessage.style.display	= 'none'
		message.innerHTML					= ''
		subTickForm.style.display		= 'flex'
		tickUserPhone.value 							= ''
		newInputs.forEach(input=>{
			input.value							= ''
		});
		const div = document.getElementById('info_div')
		if(div) div.remove()
		tickUserPhone.focus()
	}
}

[tickUserPhone, newUserPhone].forEach(item=>{
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