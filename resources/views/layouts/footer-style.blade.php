<style>
    .new_footer_area {
        position: relative;
        width: 100%;
        background-color: #f9f9f9;
    }
    
    .new_footer_top .footer_bg {
        position: relative;
        background: #cccaca;
        width: 100%;
        height: 50px; /* Reduced height */
        overflow: hidden;
        text-align: center;
    }
    .new_footer_top .footer_bg p {
        margin-top: 15px;
    }
    
    .new_footer_top .footer_bg .footer_bg_one {
        background: url("https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEia0PYPxwT5ifToyP3SNZeQWfJEWrUENYA5IXM6sN5vLwAKvaJS1pQVu8mOFFUa_ET4JuHNTFAxKURFerJYHDUWXLXl1vDofYXuij45JZelYOjEFoCOn7E6Vxu0fwV7ACPzArcno1rYuVxGB7JY6G7__e4_KZW4lTYIaHSLVaVLzklZBLZnQw047oq5-Q/s16000/volks.gif") no-repeat center center;
        width: 180px; /* Reduced width */
        height: 60px; /* Reduced height */
        background-size: 100%;
        position: absolute;
        bottom: -8px; /* Ensure it is above the copyright text */
        left: 30%;
        -webkit-animation: myfirst 22s linear infinite;
        animation: myfirst 22s linear infinite;
        cursor: pointer;
    }
    
    .new_footer_top .footer_bg .footer_bg_two {
        background: url("https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEhyLGwEUVwPK6Vi8xXMymsc-ZXVwLWyXhogZxbcXQYSY55REw_0D4VTQnsVzCrL7nsyjd0P7RVOI5NKJbQ75koZIalD8mqbMquP20fL3DxsWngKkOLOzoOf9sMuxlbyfkIBTsDw5WFUj-YJiI50yzgVjF8cZPHhEjkOP_PRTQXDHEq8AyWpBiJdN9SfQA/s16000/cyclist.gif") no-repeat center center;
        width: 40px; /* Reduced width */
        height: 45px; /* Reduced height */
        background-size: 100%;
        position: absolute;
        bottom: -1px; /* Ensure it is above the copyright text */
        left: 38%;
        -webkit-animation: myfirst 30s linear infinite;
        animation: myfirst 30s linear infinite;
        cursor: pointer;
    }
    
    @-moz-keyframes myfirst {
        0% {
            left: -25%;
        }
        100% {
            left: 100%;
        }
    }
    
    @-webkit-keyframes myfirst {
        0% {
            left: -25%;
        }
        100% {
            left: 100%;
        }
    }
    
    @keyframes myfirst {
        0% {
            left: -25%;
        }
        100% {
            left: 100%;
        }
    }

    a {
        text-decoration: none;
        color: black;
    }
    a:hover {
        color: fuchsia;
    }

    @media (max-width: 768px) {
        .new_footer_top .footer_bg .footer_bg_one {
            -webkit-animation: myfirst 15s linear infinite;
            animation: myfirst 15s linear infinite;
        }
        .new_footer_top .footer_bg .footer_bg_two {
            -webkit-animation: myfirst 20s linear infinite;
            animation: myfirst 20s linear infinite;
        }
    }
    </style>