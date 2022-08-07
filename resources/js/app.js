import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import { Amplify } from 'aws-amplify';
import AmplifyVue from '@aws-amplify/ui-vue';
import awsExports from './aws-exports';
import {
    applyPolyfills,
    defineCustomElements,
  } from '@aws-amplify/ui-components/loader';
 import '@aws-amplify/ui-vue/styles.css';


 applyPolyfills().then(() => {
    defineCustomElements(window);
  });

Amplify.configure(awsExports)

// Amplify.configure({
//     Auth: {
//         identityPoolId: 'ap-southeast-1:5fea406a-de31-4130-8e3b-a3325a0ea634', // (required) Identity Pool ID
//         region: 'ap-southeast-1' // (required) Identity Pool region
//     },
//       Interactions: {
//         bots: {
//             HitsamsBot: {
//             name: 'HitsamsBot',
//             alias: '$LATEST',
//             region: 'ap-southeast-1'
//           }
//         }
//       }
// });


createInertiaApp({
  resolve: name => require(`./Pages/${name}`),
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(AmplifyVue)
      .mount(el)
  },
})
