export default async function GetBackendAssets ({ to, from, next, store }){
    await store.dispatch('root/getAssets');
    return next()
}
