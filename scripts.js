        // JavaScript for dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            const switchers = document.querySelectorAll('.switcher');
            
            switchers.forEach(switcher => {
                switcher.addEventListener('click', function(e) {
                    // Prevent closing when clicking inside the switcher
                    e.stopPropagation();
                });
            });
            
            // Close dropdowns when clicking elsewhere
            document.addEventListener('click', function() {
                document.querySelectorAll('.dropdown').forEach(dropdown => {
                    dropdown.style.opacity = '0';
                    dropdown.style.visibility = 'hidden';
                    dropdown.style.transform = 'translateY(15px)';
                });
            });
        });

const toggleBtn = document.querySelector('.mobile-toggle');
const closeBtn = document.querySelector('.mobile-close-btn');
const mobileMenu = document.querySelector('.mobile-menu');
const overlay = document.querySelector('.mobile-menu-overlay');

toggleBtn.addEventListener('click', () => {
    mobileMenu.classList.add('active');
    overlay.classList.add('active');
});

closeBtn.addEventListener('click', () => {
    mobileMenu.classList.remove('active');
    overlay.classList.remove('active');
});

overlay.addEventListener('click', () => {
    mobileMenu.classList.remove('active');
    overlay.classList.remove('active');
});

                // Counter functionality for adults and children
        function setupCounter(decBtnId, incBtnId, valueId, min = 0, max = 10) {
            const decBtn = document.getElementById(decBtnId);
            const incBtn = document.getElementById(incBtnId);
            const valueEl = document.getElementById(valueId);
            
            let value = parseInt(valueEl.textContent);
            
            decBtn.addEventListener('click', () => {
                if (value > min) {
                    value--;
                    valueEl.textContent = value;
                }
            });
            
            incBtn.addEventListener('click', () => {
                if (value < max) {
                    value++;
                    valueEl.textContent = value;
                }
            });
        }
        
        // Set up counters
        setupCounter('adults-decrease', 'adults-increase', 'adults-value', 1, 10);
        setupCounter('children-decrease', 'children-increase', 'children-value', 0, 10);
        
        // Set minimum date to today for arrival date
        const today = new Date();
        const yyyy = today.getFullYear();
        const mm = String(today.getMonth() + 1).padStart(2, '0');
        const dd = String(today.getDate()).padStart(2, '0');
        const todayStr = `${yyyy}-${mm}-${dd}`;
        
        document.getElementById('arrival-date').min = todayStr;
        
        // Add some sample functionality to the button
        document.querySelector('.start-button').addEventListener('click', function() {
            alert('Starting your dream trip customization! This would open a detailed form in a real implementation.');
        });

        // Prevent JavaScript from interfering with dropdown hover
document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('mouseenter', function() {
        this.classList.add('hover');
    });
    
    item.addEventListener('mouseleave', function() {
        this.classList.remove('hover');
    });
});

const searchBtn = document.getElementById('searchBtn');
const searchInput = document.getElementById('searchInput');

searchBtn.addEventListener('click', () => {
  searchInput.classList.toggle('active');
  if (searchInput.classList.contains('active')) {
    searchInput.focus();
  } else {
    searchInput.blur();
  }
});

document.addEventListener('DOMContentLoaded', function() {
    const track = document.querySelector('.carousel-track');
    const cards = document.querySelectorAll('.review-card');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    const dots = document.querySelectorAll('.dot');
    
    let currentIndex = 0;
    const cardCount = cards.length;
    
    // Calculate how many cards to show based on screen width
    function getVisibleCardCount() {
        if (window.innerWidth < 576) return 1;
        if (window.innerWidth < 768) return 2;
        if (window.innerWidth < 1024) return 3;
        return 4;
    }
    
    // Update carousel position
    function updateCarousel() {
        const visibleCount = getVisibleCardCount();
        const cardWidth = cards[0].offsetWidth + 30; // width + margin
        const translateX = -currentIndex * cardWidth;
        track.style.transform = `translateX(${translateX}px)`;
        
        // Update active dot
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentIndex);
        });
        
        // Show/hide navigation buttons based on position
        prevBtn.style.visibility = currentIndex === 0 ? 'hidden' : 'visible';
        nextBtn.style.visibility = currentIndex >= cardCount - visibleCount ? 'hidden' : 'visible';
    }
    
    // Next slide
    function nextSlide() {
        const visibleCount = getVisibleCardCount();
        if (currentIndex < cardCount - visibleCount) {
            currentIndex++;
            updateCarousel();
        }
    }
    
    // Previous slide
    function prevSlide() {
        if (currentIndex > 0) {
            currentIndex--;
            updateCarousel();
        }
    }
    
    // Go to specific slide
    function goToSlide(index) {
        const visibleCount = getVisibleCardCount();
        if (index >= 0 && index <= cardCount - visibleCount) {
            currentIndex = index;
            updateCarousel();
        }
    }
    
    // Event listeners
    nextBtn.addEventListener('click', nextSlide);
    prevBtn.addEventListener('click', prevSlide);
    
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => goToSlide(index));
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
        currentIndex = 0; // Reset to first slide on resize
        updateCarousel();
    });
    
    // Initialize carousel
    updateCarousel();
    
    // Add touch support for mobile
    let startX = 0;
    let endX = 0;
    
    track.addEventListener('touchstart', function(e) {
        startX = e.touches[0].clientX;
    });
    
    track.addEventListener('touchend', function(e) {
        endX = e.changedTouches[0].clientX;
        handleSwipe();
    });
    
    function handleSwipe() {
        const swipeThreshold = 50;
        
        if (startX - endX > swipeThreshold) {
            // Swipe left - go to next
            nextSlide();
        } else if (endX - startX > swipeThreshold) {
            // Swipe right - go to previous
            prevSlide();
        }
    }
});