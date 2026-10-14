        <style>
            #popupOverlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.7);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 9999;
            }

            #popupBox {
                background: #fff;
                max-width: 600px;
                width: 90%;
                padding: 20px;
                position: relative;
                border-radius: 10px;
                text-align: center;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.4);
            }

            #popupBox img {
                max-width: 100%;
                margin-top: 10px;
            }

            .popup-nav,
            .popup-close {
                cursor: pointer;
                font-size: 20px;
                font-weight: bold;
                background: none;
                border: none;
                color: #444;
            }

            .popup-close {
                position: absolute;
                top: 10px;
                right: 15px;
            }

            .popup-nav.left {
                position: absolute;
                top: 50%;
                left: 10px;
                transform: translateY(-50%);
            }

            .popup-nav.right {
                position: absolute;
                top: 50%;
                right: 10px;
                transform: translateY(-50%);
            }
        </style>

        
        <script>
            let currentSlide = 0;
            const slides = document.querySelectorAll('.popup-slide');

            function showSlide(index) {
                slides.forEach((slide, i) => {
                    slide.style.display = i === index ? 'block' : 'none';
                });
            }

            function nextNotice() {
                currentSlide = (currentSlide + 1) % slides.length;
                showSlide(currentSlide);
            }

            function prevNotice() {
                currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                showSlide(currentSlide);
            }

            function closePopup() {
                document.getElementById('popupOverlay').style.display = 'none';
            }
            // Auto show first slide on load
            document.addEventListener('DOMContentLoaded', () => {
                showSlide(0);
            });
        </script>