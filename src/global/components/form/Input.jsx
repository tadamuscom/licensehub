import { useEffect, useState } from '@wordpress/element';
import { ErrorMessage, HelperText } from '@global';
import classNames from 'classnames';

/**
 * Add a password input
 *
 * @since 1.0.2
 *
 * @param className
 * @param error
 * @param helper
 * @param props
 * @returns {JSX.Element}
 * @constructor
 */
export const Input = ({ className, helper, result, ...props }) => {
	const [error, setError] = useState('');

	useEffect(() => {
		if (result.field === props.id) setError(result.message);
	}, [result, setError]);

	const regularClasses =
		'w-[300px] mt-2 bg-grey-200 border-2 border-black rounded-md p-2 transition duration-100 ease-in-out leading-none font-poppins';
	const hoverClasses =
		'hover:outline-none hover:shadow-none hover:bg-gray-300 hover:text-black hover:border-black';
	const focusClasses =
		'focus:outline-none focus:shadow-none focus:bg-gray-300 focus:text-black focus:border-black';
	const disabledClasses = 'disabled:bg-grey-300';
	const defaultClasses = `${regularClasses} ${hoverClasses} ${focusClasses} ${disabledClasses}`;

	return (
		<>
			<input
				{...props}
				className={classNames(
					className ? defaultClasses + ' ' + className : defaultClasses,
					{
						'border-2 border-red-500':
							error || (result.type === 'error' && !result.field),
					},
				)}
			/>
			{error ? <ErrorMessage>{error}</ErrorMessage> : ''}
			{helper ? <HelperText content={helper} /> : ''}
		</>
	);
};
