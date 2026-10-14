<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transformation in Progress - {{ company_info('company_name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&family=Space+Grotesk:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #3ebf81;
            --secondary: #147500ff;
            --accent: #FF7675;
            --dark: #2D3436;
            --light: #F5F6FA;
            --glass: rgba(255, 255, 255, 0.08);
            --glass-border: rgba(255, 255, 255, 0.2);
            --neon-glow: 0 0 15px #3ebf81;
            --transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #130F40 0%, #2D3436 100%);
            color: var(--light);
            overflow: hidden;
            position: relative;
        }

        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        .container {
            position: relative;
            z-index: 10;
            width: 90%;
            max-width: 800px;
            background: var(--glass);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 24px;
            border: 1px solid var(--glass-border);
            padding: 50px;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            animation: fadeInUp 1s ease-out forwards;
            transform-style: preserve-3d;
            perspective: 1000px;
        }

        .container::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 24px;
            padding: 2px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
            z-index: -1;
        }
  h1 {
            font-family: 'Montserrat', sans-serif;
            font-size: 3.5rem;
            margin-bottom: 10px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -1px;
        }
     .maintenance-image {
            width: 100%;
            max-width: 400px;
            margin: 30px auto;
            filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.3));
            animation: float 6s ease-in-out infinite;
        }

        .pp-infobox-title-wrapper {
            margin: 25px 0;
        }

        .pp-infobox-title-wrapper span {
            display: inline-block;
            background: var(--primary);
            color: white;
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .tagline {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: var(--light);
            font-weight: 700;
        }

        .pp-infobox-description p {
            font-size: 1.1rem;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.9);
            max-width: 600px;
            margin: 0 auto 30px;
        }
      
        /* Rest of your existing CSS remains exactly the same */
        .progress-container {
            margin: 40px 0;
        }

        .progress-text {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .progress-bar {
            height: 10px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }

        .progress-fill {
            height: 100%;
            width: 75%;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 10px;
            position: relative;
            animation: progressPulse 2s infinite ease-in-out;
        }

        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, 
                          transparent, 
                          rgba(255, 255, 255, 0.3), 
                          transparent);
            animation: progressShine 3s infinite ease-in-out;
        }

        .notify-section {
            margin-top: 40px;
            padding: 30px;
            background: var(--glass);
            border-radius: 16px;
            border: 1px solid var(--glass-border);
            position: relative;
            overflow: hidden;
        }

        .notify-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, 
                        rgba(108, 92, 231, 0.1) 0%, 
                        rgba(108, 92, 231, 0) 70%);
            animation: rotate 20s linear infinite;
            z-index: -1;
        }

        .notify-form {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .notify-form input {
            flex: 1;
            min-width: 250px;
            padding: 15px 20px;
            border: none;
            border-radius: 50px;
            background: rgba(0, 0, 0, 0.3);
            color: var(--light);
            font-size: 1rem;
            border: 1px solid var(--glass-border);
            transition: var(--transition);
        }

        .notify-form input:focus {
            outline: none;
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(0, 206, 255, 0.2);
        }

        .notify-form button {
            padding: 15px 30px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 5px 15px rgba(108, 92, 231, 0.3);
            position: relative;
            overflow: hidden;
        }

        .notify-form button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                      transparent, 
                      rgba(255, 255, 255, 0.2), 
                      transparent);
            transition: var(--transition);
        }

        .notify-form button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(108, 92, 231, 0.4);
        }

        .notify-form button:hover::before {
            left: 100%;
        }

        .social-links {
            margin-top: 40px;
        }

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .social-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--glass);
            border: 1px solid var(--glass-border);
            color: var(--light);
            font-size: 1.3rem;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .social-icon::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            opacity: 0;
            transition: var(--transition);
            z-index: -1;
        }

        .social-icon:hover {
            transform: translateY(-5px) scale(1.1);
            color: white;
            box-shadow: 0 5px 15px rgba(108, 92, 231, 0.3);
        }

        .social-icon:hover::before {
            opacity: 1;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes progressPulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.8;
            }
        }

        @keyframes progressShine {
            0% {
                transform: translateX(-100%);
            }
            100% {
                transform: translateX(100%);
            }
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 40px 30px;
            }
            h1 {
                font-size: 2.8rem;
            }
            .tagline {
                font-size: 1.1rem;
            }
            .main-gear {
                font-size: 5rem;
            }
            .secondary-gear {
                font-size: 3rem;
            }
            .tertiary-gear {
                font-size: 2rem;
            }
            .notify-form {
                flex-direction: column;
            }
            .notify-form input {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 30px 20px;
                width: 95%;
            }
            h1 {
                font-size: 2.2rem;
            }
            .tagline {
                font-size: 1rem;
            }
            .main-gear {
                font-size: 4rem;
            }
            .secondary-gear {
                font-size: 2.5rem;
            }
            .tertiary-gear {
                font-size: 1.8rem;
            }
            .social-icon {
                width: 45px;
                height: 45px;
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <div id="particles-js"></div>

    <div class="container">
     <h1>Revolution Underway!</h1>
        
        <img src="https://demo.wpbeaveraddons.com/wp-content/uploads/2018/02/main-vector.png" alt="maintenance" class="maintenance-image">
   
        <div class="pp-infobox-title-wrapper">
            <span>WE ARE COMING SOON</span>
            <h3 class="tagline">The website under maintenance!</h3>
        </div> 
        
        <div class="pp-infobox-description">
            <p>Our team is working around the clock to deliver an extraordinary new platform that will transform how you interact with our services.</p>
        </div>    
  

    </div>





    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script>
        particlesJS("particles-js", {
            "particles": {
                "number": {
                    "value": 80,
                    "density": {
                        "enable": true,
                        "value_area": 800
                    }
                },
                "color": {
                    "value": ["#6C5CE7", "#00CEFF", "#FF7675"]
                },
                "shape": {
                    "type": "circle",
                    "stroke": {
                        "width": 0,
                        "color": "#000000"
                    },
                    "polygon": {
                        "nb_sides": 5
                    }
                },
                "opacity": {
                    "value": 0.5,
                    "random": false,
                    "anim": {
                        "enable": false,
                        "speed": 1,
                        "opacity_min": 0.1,
                        "sync": false
                    }
                },
                "size": {
                    "value": 3,
                    "random": true,
                    "anim": {
                        "enable": false,
                        "speed": 40,
                        "size_min": 0.1,
                        "sync": false
                    }
                },
                "line_linked": {
                    "enable": true,
                    "distance": 150,
                    "color": "#3ebf81",
                    "opacity": 0.2,
                    "width": 1
                },
                "move": {
                    "enable": true,
                    "speed": 2,
                    "direction": "none",
                    "random": false,
                    "straight": false,
                    "out_mode": "out",
                    "bounce": false,
                    "attract": {
                        "enable": false,
                        "rotateX": 600,
                        "rotateY": 1200
                    }
                }
            },
            "interactivity": {
                "detect_on": "canvas",
                "events": {
                    "onhover": {
                        "enable": true,
                        "mode": "grab"
                    },
                    "onclick": {
                        "enable": true,
                        "mode": "push"
                    },
                    "resize": true
                },
                "modes": {
                    "grab": {
                        "distance": 140,
                        "line_linked": {
                            "opacity": 1
                        }
                    },
                    "bubble": {
                        "distance": 400,
                        "size": 40,
                        "duration": 2,
                        "opacity": 8,
                        "speed": 3
                    },
                    "repulse": {
                        "distance": 200,
                        "duration": 0.4
                    },
                    "push": {
                        "particles_nb": 4
                    },
                    "remove": {
                        "particles_nb": 2
                    }
                }
            },
            "retina_detect": true
        });

        document.querySelector('.notify-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input').value;
            
            const confirmation = document.createElement('div');
            confirmation.textContent = '✓ You\'ll be notified!';
            confirmation.style.position = 'fixed';
            confirmation.style.bottom = '20px';
            confirmation.style.left = '50%';
            confirmation.style.transform = 'translateX(-50%)';
            confirmation.style.backgroundColor = 'var(--primary)';
            confirmation.style.color = 'white';
            confirmation.style.padding = '15px 30px';
            confirmation.style.borderRadius = '50px';
            confirmation.style.boxShadow = '0 5px 15px rgba(0,0,0,0.3)';
            confirmation.style.zIndex = '1000';
            confirmation.style.animation = 'fadeInUp 0.5s ease-out';
            document.body.appendChild(confirmation);
            
            setTimeout(() => {
                confirmation.style.animation = 'fadeOut 0.5s ease-out forwards';
                setTimeout(() => confirmation.remove(), 500);
            }, 3000);
            
            this.querySelector('input').value = '';
        });
    </script>
</body>
</html>