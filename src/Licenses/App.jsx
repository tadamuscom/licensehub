import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { Button, ErrorMessage, Header } from '@global';
import { ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import { LicenseList } from '@licenses/components/LicenseList';
import { NewLicenseKey } from '@licenses/components/NewLicenseKey';

export const App = () => {
	const [isAddNew, setIsAddNew] = useState(false);

	if (lchb_license_keys.products.length < 1) {
		return (
			<ErrorMessage className="mt-4">
				{__(
					'You need to add products before you can create a license.',
					'licensehub',
				)}
			</ErrorMessage>
		);
	}

	return (
		<div className="licensehub-global">
			<Header
				pageTitle={__('License Keys', 'licensehub')}
				logoLink={lchb_license_keys.logo}
			/>
			<Button onClick={() => setIsAddNew((prev) => !prev)}>
				{isAddNew
					? __('License List', 'licensehub')
					: __('Add License', 'licensehub')}
			</Button>
			{isAddNew ? <NewLicenseKey /> : <LicenseList />}
			<ToastContainer />
		</div>
	);
};
