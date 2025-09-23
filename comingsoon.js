document.addEventListener('DOMContentLoaded', function() {
  // Countdown Timer
  const countdownElement = document.getElementById('countdown');
  const launchMessage = document.getElementById('launch-message');
  const daysElement = document.getElementById('days');
  const hoursElement = document.getElementById('hours');
  const minutesElement = document.getElementById('minutes');
  const secondsElement = document.getElementById('seconds');
  
// Set launch date (September 30, 2025)
const launchDate = new Date('2025-09-30T00:00:00').getTime();
  
  // Check localStorage for stored countdown time
  let storedTime = localStorage.getItem('countdownTime');
  let countdownTime;
  
  if (storedTime) {
    countdownTime = parseInt(storedTime);
    // If stored time is in the past, set to 0
    if (countdownTime <= 0) {
      countdownTime = 0;
    }
  } else {
    countdownTime = launchDate - new Date().getTime();
  }
  
  function updateCountdown() {
    // Calculate time units
    const days = Math.floor(countdownTime / (1000 * 60 * 60 * 24));
    const hours = Math.floor((countdownTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((countdownTime % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((countdownTime % (1000 * 60)) / 1000);
    
    // Update display
    daysElement.textContent = days.toString().padStart(2, '0');
    hoursElement.textContent = hours.toString().padStart(2, '0');
    minutesElement.textContent = minutes.toString().padStart(2, '0');
    secondsElement.textContent = seconds.toString().padStart(2, '0');
    
    // Check if countdown has ended
    if (countdownTime <= 0) {
      clearInterval(countdownInterval);
      countdownElement.style.display = 'none';
      launchMessage.style.display = 'block';
      localStorage.setItem('countdownTime', '0');
    } else {
      // Update countdown time and store in localStorage
      countdownTime -= 1000;
      localStorage.setItem('countdownTime', countdownTime.toString());
    }
  }
  
  // Initial call to set the countdown immediately
  updateCountdown();
  
  // Update countdown every second
  const countdownInterval = setInterval(updateCountdown, 1000);
  
  // Email Form Submission
  const emailForm = document.getElementById('email-form');
  const emailInput = document.getElementById('email');
  
  emailForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const email = emailInput.value.trim();
    
    if (!isValidEmail(email)) {
      showToast('Please enter a valid email address', 'error');
      return;
    }
    
    // Submit form via AJAX
    submitEmail(email);
  });
  
  function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }
  
  function submitEmail(email) {
    const formData = new FormData();
    formData.append('email', email);
    
    fetch('mailer.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showToast('Thank you! We\'ll notify you when we launch.', 'success');
        emailInput.value = '';
      } else {
        showToast('Something went wrong. Please try again.', 'error');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      showToast('Network error. Please try again.', 'error');
    });
  }
  
  // Toast Notification Function
  function showToast(message, type) {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = 'toast';
    
    // Add type class (success or error)
    toast.classList.add(type);
    
    // Show toast
    toast.classList.add('show');
    
    // Hide toast after 3 seconds
    setTimeout(function() {
      toast.classList.remove('show');
    }, 3000);
  }
});