elixir(function(mix) {
    mix
    .sass('app.scss')
    .styles([
        'menu.css'
    ])
    .scripts([
        'menu.js'
    ])
    .version(["public/css/all.css", "public/js/all.js"]);
});