import { useState } from '@wordpress/element';
import {
	Button,
	FormGroup,
	HeadingTwo,
	Input,
	Label,
	Select,
	SelectOption,
} from '@global';
import { useForms } from '@global/hooks/useForms';

export const NewLicenseKey = () => {
	const { loading, result, formData, changeFormValue, post } = useForms({
		user: '',
		product: '',
		expiresAt: '',
	});

	const [products, setProducts] = useState(() => {
		return lchb_license_keys.products.map((element, index) => {
			return <SelectOption id={element.id} label={element.name} key={index} />;
		});
	});

	const [users, setUsers] = useState(() => {
		return lchb_license_keys.users.map((element, index) => {
			return <SelectOption id={element.id} label={element.name} key={index} />;
		});
	});

	return (
		<div
			style={{
				marginBottom: '15px',
				display: 'none',
			}}
			id="tada-new-license-key">
			<HeadingTwo label="New License Key" />
			<form id="tada-add-license-key-form">
				<FormGroup>
					<Label htmlFor="lchb-user" label="User" />
					<Select
						id="lchb-user"
						name="lchb-user"
						options={users}
						value={formData.user}
						result={result}
						onChange={(e) => changeFormValue('user', e.target.value)}
					/>
				</FormGroup>
				<FormGroup>
					<Label htmlFor="lchb-product" label="Product" />
					<Select
						id="lchb-product"
						name="lchb-product"
						options={products}
						value={formData.product}
						result={result}
						onChange={(e) => changeFormValue('product', e.target.value)}
					/>
				</FormGroup>
				<FormGroup>
					<Label htmlFor="lchb-expires-at" label="Expiry Date" />
					<Input
						type="date"
						id="lchb-expires-at"
						name="lchb-expires-at"
						value={formData.expiresAt}
						result={result}
						onChange={(e) => changeFormValue('expiresAt', e.target.value)}
					/>
				</FormGroup>
				<FormGroup extraClass="tada-form-submit">
					<Button label="Save License Key" />
					<p id="tada-status" className="tada-hidden"></p>
				</FormGroup>
			</form>
		</div>
	);
};
