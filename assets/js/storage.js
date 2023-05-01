/*
    @author Pablo Bozzolo
*/

const toStorage = (data, storage = 'local') => {
    switch (storage){
        case 'session':
            st_obj = sessionStorage
            break;
        case 'local':
            st_obj = localStorage
            break;
        default:
            throw "Invalid Storage"        
    }

    st_obj.setItem('wp_sw', JSON.stringify(data))    
}

const fromStorage = (storage = 'local') => {    
    switch (storage){
        case 'session':
            st_obj = sessionStorage
            break;
        case 'local':
            st_obj = localStorage
            break;
        default:
            throw "Invalid Storage"        
    }

    return JSON.parse(st_obj.getItem('wp_sw'));    
}