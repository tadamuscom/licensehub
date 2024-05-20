import { __ } from '@wordpress/i18n';
import { Header, HeadingTwo } from '@global';
import { ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

export const App = () => {
	return (
		<div className="licensehub-global">
			<Header
				pageTitle={__('Releases', 'licensehub')}
				logoLink={window.lchb_releases.logo}
			/>
			<HeadingTwo>{__('Releases', 'licensehub')}</HeadingTwo>
			<ToastContainer />
		</div>
	);
};
