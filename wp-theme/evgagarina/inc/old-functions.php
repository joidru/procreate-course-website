<?php
//Метрика
function add_metrika_analytics()
{
?> <script>
        (function(m, e, t, r, i, k, a) {
            m[i] = m[i] || function() {
                (m[i].a = m[i].a || []).push(arguments)
            };
            m[i].l = 1 * new Date();
            for (var j = 0; j < document.scripts.length; j++) {
                if (document.scripts[j].src === r) {
                    return;
                }
            }
            k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
        })(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
        ym(92913389, "init", {
            clickmap: true,
            trackLinks: true,
            accurateTrackBounce: true,
            webvisor: true
        });
    </script>
    <meta name="p:domain_verify" content="0da52529d2ce132b7f822aa09ec73a5f" />
    <script>
        window.replainSettings = {
            id: '20e8c1b4-06c8-48aa-8dbe-6bd3d8c806ec',
            showChat: true
        };
        (function(u) {
            var s = document.createElement('script');
            s.async = true;
            s.src = u;
            var x = document.getElementsByTagName('script')[0];
            x.parentNode.insertBefore(s, x);
        })('https://widget.replain.cc/dist/client.js');
    </script>

<?php
}
add_action('wp_head', 'add_metrika_analytics');
//Убрать category
add_filter('category_link', function ($a) {
    return str_replace('category/', '', $a);
}, 99);
//отключение кнопок
function addtoany_disable_sharing_in_some_categories()
{

    if (is_page() || is_front_page() || is_product() || is_shop() || is_checkout() || is_account_page() || 'course' == get_post_type() || 'lesson' == get_post_type()) {
        return true;
    }
}
add_filter('addtoany_sharing_disabled', 'addtoany_disable_sharing_in_some_categories');
//Изменение ссылки в профиле
function disable_gravatar_link()
{
?>
    <script>
        jQuery(document).ready(function($) {
            $(".kt-link-to-gravatar").attr('href', '/');
            $(".kt-link-to-gravatar").on("click", function(e) {
                e.preventDefault(); // Запрещает действие по умолчанию (переход по ссылке)
                e.stopPropagation(); // Останавливает всплытие события
            });
        });
    </script>
<?php
}
add_action('wp_footer', 'disable_gravatar_link');
