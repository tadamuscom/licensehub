import { useState, useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { Header, HeadingTwo, getQueryParameter } from '@global';
import { ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import { EditRelease } from './components/core/EditRelease';
import { ReleaseList } from './components/core/ReleaseList';

export const App = () => {
	const [isEdit, setIsEdit] = useState(false);

	useEffect(() => {
		if (window.location.search.includes('id')) setIsEdit(true);
	}, [setIsEdit]);

	if (isEdit) {
		return (
			<div className="licensehub-global">
				<Header
					pageTitle={__('Releases', 'licensehub')}
					logoLink={window.lchb_releases.logo}
				/>
				<EditRelease
					releaseID={getQueryParameter('id')}
					setIsEdit={setIsEdit}
				/>
				<ToastContainer />
			</div>
		);
	}

	return (
		<div className="licensehub-global">
			<Header
				pageTitle={__('Releases', 'licensehub')}
				logoLink={window.lchb_releases.logo}
			/>
			<HeadingTwo>{__('Releases', 'licensehub')}</HeadingTwo>
			<ReleaseList setIsEdit={setIsEdit} />
			<ToastContainer />
		</div>
	);
};
