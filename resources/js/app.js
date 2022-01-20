require('./bootstrap');

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

let permsMixin = {
    userCan(permissions) {
        var allPermissions = this.$page.props.perms;

        if (typeof permissions == 'string') {
            return allPermissions.includes(permissions)
        }

        var hasPermission = false;
        permissions.forEach(function(perm){
            if(!hasPermission && allPermissions.includes(perm)) {
                hasPermission = true;
            }
        });

        return hasPermission;
    }
};

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .mixin({ methods: { route } })
            .mixin({ methods: { ...permsMixin } })
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
