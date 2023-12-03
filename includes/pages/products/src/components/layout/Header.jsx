import React from 'react';

function Header( props ) {
    return (
        <div>
            <div className="tada-flex-row tada-admin-header">
                <div>
                    <a href="https://tadamus.com" target='_blank'><img src={ '' + lchb_products.logo }  alt='tadamus.com logo' width="200px" /></a>
                </div>
                <div>
                    <h1 className="tada-admin-page-heading">LicenseHub - { props.pageTitle }</h1>
                </div>
            </div>
        </div>
    );
}

export default Header;