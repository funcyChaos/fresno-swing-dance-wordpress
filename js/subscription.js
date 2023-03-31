const messageWrap	= document.getElementById('main_message')
const message			= document.getElementById('message_element')
let		result			= false
const tickForm		= document.getElementById('sub_tick_form')
const phone				= document.getElementById('user_phone')
const newUserForm	= document.getElementById('new_user_form')
const newPhone		= document.getElementById('new_phone')

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
			messageWrap.style.display	= 'flex'
			tickForm.style.display				= 'none'
			result										= true
			// setTimeout(()=>{
			// 	if(result)clearForm()
			// }, 10000)
		}else{
			message.innerHTML					= `${obj.first_name} has ${obj.vouchers} vouchers left`
			messageWrap.style.display	= 'flex'
			tickForm.style.display				= 'none'
			result										= true
			// setTimeout(()=>{
			// 	if(result)clearForm()
			// }, 10000)
		}
	})
})

newUserForm.addEventListener('submit', e=>{
	e.preventDefault()
	data = new FormData(newUserForm)
	// console.log(data.getAll('first_name'),data.getAll('last_name'))
	const firstName = data.getAll('first_name')[0]
	const lastName 	= data.getAll('last_name')[0]
	const number		= data.getAll('new_phone')[0].replace(/\D/g,'')
	// console.log(firstName,lastName,number)
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
			console.log(obj.subscriber[0][0])
			const fName = document.createElement('p')
			const lName = document.createElement('p')
			const pNum	= document.createElement('p')
			fName.innerHTML	= `First: ${obj.subscriber[0][0]}`
			lName.innerHTML	= `Last: ${obj.subscriber[0][1]}`
			pNum.innerHTML	= `Phone: ${obj.subscriber[0][2]}`
			messageWrap.appendChild(fName)
			messageWrap.appendChild(lName)
			messageWrap.appendChild(pNum)
			message.innerHTML 				= 'Subscriber already exists'
			messageWrap.style.display	= 'flex'
			newUserForm.style.display				= 'none'
			result										= true
		}
	})
})

messageWrap.addEventListener('click', clearForm)

function clearForm(){
	messageWrap.style.display	= 'none'
	tickForm.style.display				= 'flex'
	result										= false
	phone.focus()
	phone.value = ''
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