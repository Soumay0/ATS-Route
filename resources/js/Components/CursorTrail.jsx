import { useEffect, useRef } from 'react';
import { useTheme } from '../Contexts/ThemeContext';

export default function CursorTrail() {
    const canvasRef = useRef(null);
    const { theme } = useTheme();

    useEffect(() => {
        const canvas = canvasRef.current;
        const ctx = canvas.getContext('2d');
        let animationFrameId;
        
        const resize = () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        };
        window.addEventListener('resize', resize);
        resize();

        const trail = [];
        const handleMouseMove = (e) => {
            trail.push({ x: e.clientX, y: e.clientY, alpha: 1 });
        };
        window.addEventListener('mousemove', handleMouseMove);

        const draw = () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            // Define colors based on the theme (vibrant cyan for dark, vibrant blue for light)
            const rgbColor = theme === 'dark' ? '0, 240, 255' : '2, 132, 199';
            
            for (let i = 0; i < trail.length; i++) {
                const p = trail[i];
                p.alpha -= 0.04; // Fade out speed
                
                if (p.alpha <= 0) {
                    trail.splice(i, 1);
                    i--;
                    continue;
                }
                
                ctx.beginPath();
                // Radius scales with alpha
                ctx.arc(p.x, p.y, Math.max(0, p.alpha * 5), 0, Math.PI * 2);
                ctx.fillStyle = `rgba(${rgbColor}, ${p.alpha})`;
                ctx.shadowBlur = 12;
                ctx.shadowColor = `rgba(${rgbColor}, ${p.alpha})`;
                ctx.fill();
            }
            
            animationFrameId = requestAnimationFrame(draw);
        };
        draw();

        return () => {
            window.removeEventListener('resize', resize);
            window.removeEventListener('mousemove', handleMouseMove);
            cancelAnimationFrame(animationFrameId);
        };
    }, [theme]);

    return (
        <canvas
            ref={canvasRef}
            className="fixed inset-0 pointer-events-none z-[9999]"
        />
    );
}
