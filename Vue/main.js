import {createApp, markRaw} from 'vue';
import { createPinia, PiniaVuePlugin  } from 'pinia'


//-------------PrimeVue Imports

import PrimeVue from "primevue/config";
import AutoComplete from 'primevue/autocomplete';
import Accordion from 'primevue/accordion';
import AccordionTab from 'primevue/accordiontab';
import Badge from 'primevue/badge';
import BadgeDirective from "primevue/badgedirective";
import Button from 'primevue/button';
import Calendar from 'primevue/calendar';
import Card from 'primevue/card';
import Checkbox from 'primevue/checkbox';
import Chips from 'primevue/chips';
import Column from 'primevue/column';
import ConfirmDialog from 'primevue/confirmdialog';
import ConfirmationService from 'primevue/confirmationservice';
import DataTable from 'primevue/datatable';
import DialogService from 'primevue/dialogservice'
import Divider from 'primevue/divider';
import Dropdown from 'primevue/dropdown';
import Editor from 'primevue/editor';
import FileUpload from 'primevue/fileupload';
import InputSwitch from 'primevue/inputswitch';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import Menu from 'primevue/menu';
import Message from 'primevue/message';
import Paginator from 'primevue/paginator';
import Panel from 'primevue/panel';
import Password from 'primevue/password';
import ProgressBar from 'primevue/progressbar';
import RadioButton from 'primevue/radiobutton';
import Ripple from 'primevue/ripple';
import Sidebar from 'primevue/sidebar';
import StyleClass from 'primevue/styleclass';
import Textarea from 'primevue/textarea';
import Toast from 'primevue/toast';
import ToastService from 'primevue/toastservice';
import Tag from 'primevue/tag';
import Tooltip from 'primevue/tooltip';
import TreeSelect from 'primevue/treeselect';
//-------------/PrimeVue Imports



//-------------APP
import App from './layouts/App.vue'
import router from './routes/router'

const app = createApp(App);

const pinia = createPinia();
pinia.use(({ store }) => {
    store.$router = markRaw(router)
});
app.use(pinia);
app.use(PiniaVuePlugin);
app.use(router);
//-------------/APP


//-------------PrimeVue Use
app.use(PrimeVue, { ripple: true });
app.use(ConfirmationService);
app.use(ToastService);
app.use(DialogService);

app.directive('tooltip', Tooltip);
app.directive('badge', BadgeDirective);
app.directive('ripple', Ripple);
app.directive('styleclass', StyleClass);

app.component('Accordion', Accordion);
app.component('AccordionTab', AccordionTab);
app.component('AutoComplete', AutoComplete);
app.component('Badge', Badge);
app.component('Button', Button);
app.component('Calendar', Calendar);
app.component('Card', Card);
app.component('Checkbox', Checkbox);
app.component('Chips', Chips);
app.component('Column', Column);
app.component('ConfirmDialog', ConfirmDialog);
app.component('DataTable', DataTable);
app.component('Divider', Divider);
app.component('Dropdown', Dropdown);
app.component('Editor', Editor);
app.component('FileUpload', FileUpload);
app.component('InputNumber', InputNumber);
app.component('InputSwitch', InputSwitch);
app.component('InputText', InputText);
app.component('Menu', Menu);
app.component('Message', Message);
app.component('Paginator', Paginator);
app.component('Panel', Panel);
app.component('Password', Password);
app.component('ProgressBar', ProgressBar);
app.component('RadioButton', RadioButton);
app.component('Sidebar', Sidebar);
app.component('Tag', Tag);
app.component('Textarea', Textarea);
app.component('Toast', Toast);
app.component('TreeSelect', TreeSelect);
//-------------/PrimeVue Use


app.mount('#appCms')


export { app }
