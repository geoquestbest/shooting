if (document.querySelector('.form-field__calendar img')) {
  document.querySelectorAll('.form-field__calendar img').forEach(el=>{
      el.addEventListener('click', (e)=>{
      e.currentTarget.parentElement.querySelector('input').showPicker()
    })
  })
}