export default async function GetBackendAssets ({ to, from, next, store }){
    await store.dispatch('root/getAssets');
    await store.dispatch('root/getPermissions');
    return next()
}
