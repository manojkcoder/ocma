import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],
    theme: {
        screens: {
            sm: "480px",
            md: "768px",
            lg: "976px",
            xl: "1440px"
        },
        colors: {
            "gray": "#eaeaea",
            "darkBlue": "#2f6680",
            "lightBlack": "#525252",
            "white": "#FFFFFF",
            "red": "#F00000",
            "orange": "#f48521",
            "lightGreen": "#dff0d8"
        },
        extend: {
            fontFamily: {
                sans: ['Figtree',...defaultTheme.fontFamily.sans],
            }
        }
    },
    plugins: [forms],
};