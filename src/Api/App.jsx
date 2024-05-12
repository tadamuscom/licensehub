import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { Button, Header } from '@global';
import { ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import { KeyList } from '@api/components/KeyList';
import { NewAPIKey } from '@api/components/NewAPIKey';

export const App = () => {
	const [isAddNew, setIsAddNew] = useState(false);

	return (
		<div className="licensehub-global">
			<Header
				pageTitle={__('API Keys', 'licensehub')}
				logoLink={lchb_api_keys.logo}
			/>
			<Button onClick={() => setIsAddNew((prev) => !prev)}>
				{isAddNew
					? __('API Keys List', 'licensehub')
					: __('Add API Key', 'licensehub')}
			</Button>
			{isAddNew ? <NewAPIKey /> : <KeyList />}
			<ToastContainer />
		</div>
	);
};
