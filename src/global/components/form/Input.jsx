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
export const Input = ({ className, error, helper, ...props }) => {
	const defaultClasses = 'tada-input';

	return (
		<>
			<input
				autoComplete="off"
				{...props}
				className={classNames(
					className ? defaultClasses + ' ' + className : defaultClasses,
					{ 'tada-field-error': error },
				)}
			/>
			{error ? <ErrorMessage error={error} /> : ''}
			{helper ? <HelperText content={helper} /> : ''}
		</>
	);
};
