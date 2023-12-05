export const triggerError = ( elementId, message ) => {
    const element = document.getElementById( elementId );
    const parent = element.parentNode;

    if( ! element.classList.contains( 'tada-field-error' ) ){
        element.classList.add( 'tada-field-error' );
    }

    let go = true;

    parent.childNodes.forEach( ( element ) => {
        if( element.classList.contains( 'tada-error-message' ) ){
            go = false;

            return;
        }
    } );

    if( go ){
        const errorMessage = document.createElement( 'p' );
        errorMessage.innerText = message;
        errorMessage.classList.add( 'tada-error-message' );
        parent.appendChild( errorMessage );
    }
}

export const resetForm = ( formElement ) => {
    const status = document.getElementById( 'tada-status' );
    status.innerText = '';

    if( status.classList.contains( 'tada-hidden' ) ){
        status.classList.remove( 'tada-hidden' );
    }

    resetErrorGroup( document.querySelectorAll( '#' + formElement.id + ' input[type="text"]' ) );
    resetErrorGroup( document.querySelectorAll( '#' + formElement.id + ' input[type="password"]' ) );

    const errorMessages = document.querySelectorAll( '#' + formElement.id + ' .tada-error-message' )

    errorMessages.forEach( ( error ) => {
        error.remove();
    } );
}

const resetErrorGroup = ( group ) => {
    group.forEach( ( input ) => {
        if( input.classList.contains( 'tada-field-error' ) ){
            input.classList.remove( 'tada-field-error' );
        }
    } );
}