import React, { useState } from 'react';
import TableHeader from "./TableHeader";
import TableRow from "./TableRow";
import HeadingTwo from "./typography/HeadingTwo";

function ProductTable( props ) {
    const [ products, setProducts ] = useState( [] );

    const preProducts = [];
    const fields = [];

    lchb_products.fields.forEach( ( element, index ) => {
        fields.push( <TableHeader content={ element } key={ index } /> );
    } );

    lchb_products.products.forEach( ( element, index ) => {
        preProducts.push( <TableRow columns={ element } key={ index } /> );
    } );

    setProducts( preProducts );

    return (
        <div>
            <HeadingTwo label='Products' />
            <table className='tada-table' style={{
                marginTop: '10px'
            }}>
                <thead>
                    <tr>
                        { fields }
                    </tr>
                </thead>
                <tbody>
                    { products }
                </tbody>
            </table>
        </div>
    );
}

export default ProductTable;