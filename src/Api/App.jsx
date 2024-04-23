import { Header, HeadingTwo, LinkButton, Table } from '@global';
import { NewAPIKey } from '@api/components/NewAPIKey';

export const App = () => {
	const newOnClick = (event) => {
		event.preventDefault();

		const newProduct = document.getElementById('tada-new-api-key');

		event.target.style.display = 'none';
		newProduct.style.display = 'inherit';
	};

	return (
		<div>
			<Header pageTitle="API Keys" />
			<LinkButton click={newOnClick} label="Add API Key" />
			<NewAPIKey />
			<HeadingTwo label="API Keys" />
			<Table headers={lchb_api_keys.fields} rows={lchb_api_keys.keys} />
		</div>
	);
};
