/**
 * Add a submit input styled like a button
 *
 * @since 1.0.2
 *
 * @param className
 * @param children
 * @param props
 * @returns {JSX.Element}
 * @constructor
 */
export const Button = ({ className, children, ...props }) => {
	const regularClasses =
		'bg-tadaBlue text-white font-kanit border-0 text-sm mt-4 px-4 py-2 uppercase rounded-lg cursor-pointer transition-all';
	const hoverClasses = 'hover:bg-tadaLighterBlue';
	const disabledClasses =
		'disabled:bg-tadaGray disabled:text-black disabled:cursor-not-allowed';
	const defaultClasses = `${regularClasses} ${hoverClasses} ${disabledClasses}`;

	return (
		<button
			className={className ? className + ' ' + defaultClasses : defaultClasses}
			{...props}>
			{children}
		</button>
	);
};
