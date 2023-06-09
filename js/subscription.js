const mainMessage			= document.getElementById('main_message')
const message					= document.getElementById('message_element')
const subTickForm			= document.getElementById('sub_tick_form')
const tickUserPhone		= document.getElementById('tick_user_phone')
const subRenewForm		= document.getElementById('sub_renew_form')
const subRenewPhone		= document.getElementById('renew_user_phone')
const subCheckForm		= document.getElementById('sub_check_form')
const checkUserPhone	= document.getElementById('check_user_phone')
const newUserForm			= document.getElementById('new_user_form')
const newUserPhone		= document.getElementById('new_phone')
const newInputs				= document.querySelectorAll('.new-user-inputs')
const uptUserForm			= document.getElementById('update_user_form')
const uptUserSearch		= document.getElementById('user_update_search')
const uptFormInputs		= [
	document.getElementById('upt_first_name'),
	document.getElementById('upt_last_name'),
	document.getElementById('upt_phone'),
	document.getElementById('upt_vouchers'),
]
const forms						= [subTickForm, newUserForm]
let		showMessage			= false
let		currentTab			= subTickForm
let		messageTimer		= null
let		typingTimer
let		currentUserName

/**
 * Subscriber Tick Form Submit Handler
 * ***********************************
 * If the subscriber exists, one voucher will be removed from their total
 */
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
		console.log('Tick Submit', obj)
		if(obj.error){
			message.innerHTML 				= obj.error
			toggleMessage()
			messageTimer = setTimeout(()=>{
				if(showMessage)toggleMessage()
			}, 10000)
		}else{
			message.innerHTML					= `${obj.first_name} has ${obj.vouchers} vouchers left`
			toggleMessage()
			messageTimer = setTimeout(()=>{
				if(showMessage)toggleMessage()
			}, 10000)
		}
	})
})

document.getElementById('new_user_btn').addEventListener('click', ()=>{tabSwitch(newUserForm)})
document.getElementById('nuf_close_btn').addEventListener('click', ()=>{tabSwitch(subTickForm)})
/**
 * New Subscriber Form Submit Handler
 * **********************************
 * Creates new subscriber if one doesn't exist already
 * 	And shows remaining vouchers or else an error
 */
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
		console.log('New User Submit', obj)
		if(obj.error){
			if(obj.subscriber){
				messageInfoDiv(
						obj.error,							//mes
						obj.subscriber[0][1],		//first
						obj.subscriber[0][2],		//last
						obj.subscriber[0][3],		//phone
						obj.subscriber[0][4],		//vouchers
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

document.getElementById('renew_user_btn').addEventListener('click', ()=>{tabSwitch(subRenewForm)})
document.getElementById('close_renew_btn').addEventListener('click',e=>{
	e.preventDefault()
	tabSwitch(subTickForm)
})
/**
 * Subscriber Renew Form Handler
 * *****************************
 * Sets the subscribers vouchers to 3 if phone number matches
 * 	And shows the update or else an error
 */
subRenewForm.addEventListener('submit', e=>{
	e.preventDefault()
	const number = subRenewPhone.value.replace(/\D/g,'')
	fetch(`${wpVars.homeURL}/wp-json/subscription/v1/renew-user`,{
		method: 'PATCH',
		headers: {
			'Content-Type': 'application/json',
			'X-WP-Nonce':		wpVars.nonce,
		},
		body: JSON.stringify({
			phone: number,
		})
	})
	.then(res=>res.json())
	.then(obj=>{
		console.log('Renew Submit', obj)
		if(obj.error){
			message.innerHTML = obj.error
			toggleMessage()
		}else{
			message.innerHTML = `${obj.patch[1]} has 3 vouchers left`
			toggleMessage()
		}
	})
})

document.getElementById('check_vouchers_btn').addEventListener('click', ()=>{tabSwitch(subCheckForm)})
document.getElementById('close_check_btn').addEventListener('click', e=>{
	e.preventDefault()
	tabSwitch(subTickForm)
})
/**
 * Subscriber Check Vouchers Form
 * ******************************
 * Shows subscribers remaining vouchers or else an error
 */
subCheckForm.addEventListener('submit', e=>{
	e.preventDefault()
	const number = checkUserPhone.value.replace(/\D/g,'')
	fetch(`${wpVars.homeURL}/wp-json/subscription/v1/by-number/${number}`,{
		method: 'GET',
		headers: {
			'Content-Type': 'application/json',
			'X-WP-Nonce':		wpVars.nonce,
		},
	})
	.then(res=>res.json())
	.then(obj=>{
		console.log('Check Vouchers Submit', obj)
		if(obj.error){
			message.innerHTML	= obj.error
			toggleMessage()
		}else{
			message.innerHTML	= `${obj.first_name} has ${obj.vouchers} vouchers left`
			toggleMessage()
		}
	})
})

document.getElementById('edit_subscriber_btn').addEventListener('click', ()=>{tabSwitch(uptUserForm)})
document.getElementById('uuf_close_btn').addEventListener('click', ()=>{tabSwitch(subTickForm)})
/**
 * Search For User Event Handlers (This should be refactored to subscriber)
 * ************************
 * These handlers will trigger the doneTyping function (maybe should be renamed) when
 * 	The user has finished typing for 1 second
 */
uptUserSearch.addEventListener('keyup', ()=>{
	clearTimeout(typingTimer)
	typingTimer	= setTimeout(searchForUser, 1000);
})
uptUserSearch.addEventListener('keydown', ()=>{clearTimeout(typingTimer)})
/**
 * Update User Form Event Handler (This should be refactored to subscriber)
 * ******************************
 * This will update any changed information on the selected user
 */
uptUserForm.addEventListener('submit', e=>{
	e.preventDefault()
	let firstName	= uptFormInputs[0].value
	let lastName	= uptFormInputs[1].value
	let number		= uptFormInputs[2].value
	let vouchers	= uptFormInputs[3].value
	fetch(`${wpVars.homeURL}/wp-json/subscription/v1/update-user`,{
		method: 'PATCH',
		headers: {
			'Content-Type': 'application/json',
			'X-WP-Nonce':		wpVars.nonce,
		},
		body: JSON.stringify({
			current:		currentUserName,
			first_name:	firstName,
			last_name:	lastName,
			phone:			number,
			vouchers: 	vouchers,
		})
	})
	.then(res=>res.json())
	.then(obj=>{
		console.log('Update User Submit', obj)
		currentUserName = firstName
		if(obj.patch){
			flashInputs()
		}
	})
})

mainMessage.addEventListener('click', toggleMessage);

/**
 * Phone Number Format On Input Event Handler
 * ******************************************
 */
[tickUserPhone, newUserPhone, checkUserPhone, subRenewPhone].forEach(item=>{
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

/**
 * Search For User on keyup function
 * *********************************
 */
function searchForUser(){
	fetch(`${wpVars.homeURL}/wp-json/subscription/v1/update-user?var=${uptUserSearch.value}`,{
		method: 'GET',
		headers: {
			'Content-Type': 'application/json',
			'X-WP-Nonce':		wpVars.nonce,
		},
	})
	.then(res=>res.json())
	.then(obj=>{
		if(obj.subscriber){
			for (let i = 0; i < uptFormInputs.length; i++){
				if(uptFormInputs[i] != obj.subscriber[0][i]){
					flashInputs()
				}
				uptFormInputs[i].value 		= obj.subscriber[0][i]
				uptFormInputs[i].disabled	= false
			}
			uptUserForm.querySelector('input[type=submit]').disabled	= false
			currentUserName							= uptFormInputs[0].value
		}
		else{
			for (let i = 0; i < uptFormInputs.length; i++){
				uptFormInputs[i].value		= ''
				uptFormInputs[i].disabled	= true
				flashInputs()
			}
			uptFormInputs[0].value = 'No user found'
			uptUserForm.querySelector('input[type=submit]').disabled	= true
		}
	})
}
function flashInputs(){
	for(const input of uptFormInputs){
		input.classList.add('flash')
		setTimeout(() => {
			input.classList.remove('flash')
		}, 200);
	}
}

function tabSwitch(tab){
	if(showMessage)toggleMessage()
	currentTab.classList.add('main-hide')
	currentTab	= tab
	currentTab.classList.remove('main-hide')
}

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

function toggleMessage(){
	if(!showMessage){
		showMessage										= true
		mainMessage.classList.remove('main-hide')
		currentTab.classList.add('main-hide')
	}else{
		showMessage										= false
		clearTimeout(messageTimer)
		mainMessage.classList.add('main-hide')
		message.innerHTML							= ''
		currentTab.classList.remove('main-hide');
		currentTab.querySelector('input[type=tel]').value = ''
		newInputs.forEach(input=>{
			input.value									= ''
		});
		const div = document.getElementById('info_div')
		if(div) div.remove()
		tickUserPhone.focus()
	}
}